<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Job;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Resend\Laravel\Facades\Resend;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia::render('Invoice/Index', [
            'invoices' => Invoice::orderBy('created_at', 'DESC')
                ->with('items')->where('user_id', Auth::id())
            ->paginate(),
        ]);
    }

    public function viewInvoice(Invoice $invoice)
    {
        if ($invoice->user_id !== Auth::id()) return;
        $user = User::with('business')->findOrFail(Auth::id());
        $invoice->load('items');
        return inertia::render('Invoice/View', [
            'invoice' => $invoice,
            'user'=> $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Job $job)
    {
        $job->load('customer', 'business');
        return $job;
    }


    public function downloadInvoice($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);

        // Decode snapshots into PHP objects
        $business = json_decode($invoice->business_snapshot);
        $customer = json_decode($invoice->customer_snapshot);
        $job = $invoice->job_snapshot ? json_decode($invoice->job_snapshot) : null;

        $pdf = PDF::loadView('pdf.Invoice', [
            'invoice' => $invoice,
            'business' => $business,
            'customer' => $customer,
            'job' => $job
        ]);

        return $pdf->download('invoice-'.$invoice->invoice_number.'.pdf');
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        DB::beginTransaction();

        $job = Job::with('customer', 'business')->findOrFail($request->job_id);
        $business = $job->business;
        $customer = $job->customer;

        $logoPath = $business->logo_path;
        $logoFilename = basename($logoPath);
        $invoiceLogoPath = 'invoices/logos/' . $logoFilename;

        if (!Storage::exists($invoiceLogoPath)) {
            Storage::copy($logoPath, $invoiceLogoPath);
        }

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'user_id'        => Auth::id(),
            'customer_id'    => $customer->id,
            'job_id'         => $job->id,

            'customer_snapshot' => json_encode([
                'id'      => $customer->id,
                'name'    => $customer->name,
                'company' => $customer->company,
                'email'   => $customer->email,
                'phone'   => $customer->phone,
            ]),

            'job_snapshot' => json_encode([
                'id'          => $job->id,
                'title'       => $job->job_title,
                'description' => $request->job_description ?? $job->description,
                'start_date'  => $job->start_date,
                'due_date'    => $job->due_date,
                'status'      => $job->status,
            ]),

            'business_snapshot' => json_encode([
                'id'      => $business->id,
                'name'    => $business->business_name,
                'email'   => $business->business_email,
                'phone'   => $business->business_phone,
                'address' => $business->business_address,
                'logo'    => $invoiceLogoPath,
            ]),

            'issue_date' => $request->issue_date,
            'due_date'   => $request->due_date,
            'status'     => 'pending',

            'subtotal'   => $request->total['subtotal'],
            'tax'        => $request->total['vat'],
            'discount'   => $request->total['discount'] ?? 0,
            'total'      => $request->total['total'],
            'currency'   => $request->currency ?? 'NGN',
            'notes'      => $request->notes ?? null,
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'name'        => $item['name'],
                'description' => $item['description'] ?? '',
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }
        DB::commit();

        return response()->json([
            'success' => true,
            'invoice' => $invoice
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {

        $data = $request->validate([
            'selectedStatus' => 'required|string|in:paid,pending,overdue,cancelled',
        ]);
        $invoice->status = $data['selectedStatus'];
        $invoice->save();
        return response()->json(['messsage'=> 'Invoice updated']);
    }

    /**
     * Remove the specified resource from storage.
     */



    public function sendInvoiceInvoice(Request $request, Invoice $invoice)
    {

        $replyTo = $request->replyToEmail;
        $authUser = User::with('business')->find(auth()->id());
        $allowedEmails = [
            $authUser->email,
            $authUser->business->business_email ?? null,
            json_decode($invoice->business_snapshot, true)['email']?? null
        ];
        if (!in_array($replyTo, array_filter($allowedEmails))) {
            return response()->json(['message' => 'Invalid reply-to email'], 403);
        }

        $email = $request->email;
        $message = $request->message ?? 'Please find attached invoice for your necessary action.';
        $invoiceId = $invoice->id;
        $fromName = $request->from_name?? $authUser->business->name;
        $subject = $request->subject;

        $invoice->load('items')->findOrFail($invoiceId);
        $business = json_decode($invoice->business_snapshot);
        $job = json_decode($invoice->job_snapshot);
        $customer = json_decode($invoice->customer_snapshot);
        $pdf = PDF::loadView('pdf.Invoice', [
            'invoice' => $invoice,
            'business' => $business,
            'job' => $job,
            'customer' => $customer,
        ]);

        $pdfContent = base64_encode($pdf->output());
        Resend::emails()->send([
            'from' => $fromName.'<user@entroly.com.ng>',
            'reply_to' => $request->reply,
            'to' => $email,
            'subject' => $subject,
            'html' => '<p>'.$message.'</p>',
            'attachments' => [
                [
                    'filename' => 'invoice-'.$invoice->invoice_number.'.pdf',
                    'content' => $pdfContent,
                    'type' => 'application/pdf'
                ]
            ]
        ]);
        return response()->json(['status' => 'success', 'message' => 'Email with invoice sent']);
    }





    public function destroy(Invoice $invoice)
    {
        //
    }
}
