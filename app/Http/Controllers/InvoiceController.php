<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

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
        $invoice->load('items');
        return inertia::render('Invoice/View', [
            'invoice' => $invoice,
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
    public function destroy(Invoice $invoice)
    {
        //
    }
}
