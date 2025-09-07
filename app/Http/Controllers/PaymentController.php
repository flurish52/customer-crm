<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateReceiptJob;
use App\Models\Invoice;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Resend\Laravel\Facades\Resend;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($ref_number)
    {
        $receipt = Payment::where('reference_number', $ref_number)->first();
           if ($receipt && $receipt->pdf_path && Storage::disk('public')->exists($receipt->pdf_path)) {
            return response()->download(
                Storage::disk('public')->path($receipt->pdf_path)
            );
        }
        $payment = Payment::where('reference_number', $ref_number)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        GenerateReceiptJob::dispatch($payment->id);
        return response()->json([
            'message' => 'Your receipt is being generated. Please try again.'
        ]);


    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function returnPayments()
    {
        return inertia::render('User/Payments', [
            'payments' => Payment::orderBy('created_at', 'DESC')->with('invoice')
                ->where('user_id', Auth::id())
                ->get(),
            'invoices' => Invoice::orderBy('invoice_number', 'DESC')->where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $invoice = Invoice::with('job', 'customer')
            ->where('id', $request->invoiceId)
            ->first();
        if ($invoice->user_id !== Auth::id()) return response()
            ->json(['status' => 'error', 'message' => 'Unauthorized'],
                403);
        try {
            DB::beginTransaction();
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'invoice_id' => $request->invoiceId,
                'customer_id' => $invoice->customer->id,
                'job_id' => $invoice->job->id,
                'amount_in_business_currency' => $request->amount_in_business_currency,
                'amount_in_invoice_currency' => $request->amount_in_invoice_currency,
                'paid_at' => $request->date ?? now(),
                'method' => $request->payment_method,
                'notes' => $request->notes ?? null,
                'reference_number' => 'RCPT-' . strtoupper(Str::uuid()->toString()),
            ]);
            $totalPayment = Payment::where('invoice_id', $request->invoiceId)->sum('amount_in_invoice_currency');
            $balance = $invoice->total - $totalPayment;

            if ($balance <= 0) {
                $invoice->update(['status' => 'paid']);
            } elseif ($balance > 0 && $balance < $invoice->total) {
                $invoice->update(['status' => 'partially_paid']);
            } else {
                $invoice->update(['status' => 'pending']);
            }
            GenerateReceiptJob::dispatch($payment->id);
            DB::commit();
            $receipt = [
                'receipt_number' => $payment->reference_number,
                'invoice_number' => $invoice->invoice_number,
                'customer' => json_decode($invoice->customer_snapshot)->name ?? 'N/A',
                'customer_email' => json_decode($invoice->customer_snapshot)->email ?? 'N/A',
                'business' => json_decode($invoice->business_snapshot)->name ?? 'N/A',
                'business_email' => json_decode($invoice->business_snapshot)->email ?? 'N/A',
                'job' => json_decode($invoice->job_snapshot)->title ?? 'N/A',
                'payment_amount' => number_format($payment->amount_in_invoice_currency, 2),
                'total_amount' => number_format($invoice->total, 2),
                'total_paid' => number_format($totalPayment, 2),
                'remaining_balance' => number_format($balance, 2),
                'method' => $payment->method,
                'date' => $payment->paid_at,
                'currency'=>$invoice->currency,
                'note'=>$payment->note,
            ];
            return response()->json([
                'status' => 'success',
                'message' => 'Payment recorded successfully.',
                'receipt' => $receipt
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }



    public function resendReceipt(Request $request, $ref_number)
    {
        $payment = Payment::where('reference_number', $ref_number)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$payment->pdf_path || !Storage::disk('public')->exists($payment->pdf_path)) {
            GenerateReceiptJob::dispatch($payment->id);
            return response()->json(['status' => 'error', 'message' => 'Receipt not found, try again'], 404);
        }
        $request->validate([
            'subject' => 'required|string',
            'from_name' => 'required|string',
            'message' => 'required|string',
            'sendToEmail' => 'required|email',
            'replyToEmail' => 'required|email',
        ]);
        $invoice = $payment->invoice;
        $customer = json_decode($invoice->customer_snapshot, true);
        $business = json_decode($invoice->business_snapshot, true);

        Resend::emails()->send([
            'from' => $request->from_name . ' <user@entroly.com.ng>',
            'to' => $request->sendToEmail,
            'reply_to' => $request->replyToEmail,
            'bcc' => [$request->replyToEmail] ?? null,
            'subject' => $request->subject,
            'html' => '<p>Dear ' . ($customer['name'] ?? 'Customer') . ',</p>'
                . '<p>' . nl2br(e($request->message)) . '</p>'
                . '<p>Thank you for your business.</p>',
            'attachments' => [
                [
                    'content' => base64_encode(Storage::disk('public')->get($payment->pdf_path)),
                    'filename' => 'receipt-' . ($payment->reference_number ?? $payment->id) . '.pdf',
                ]
            ]

        ]);

        return response()->json(['status' => 'success', 'message' => 'Receipt sent successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Payment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            return 'You are not authorized to perform this action';
        }

        $payment->is_invalid = true || 1;
        $payment->save();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
