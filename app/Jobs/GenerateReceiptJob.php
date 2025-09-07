<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function handle()
    {
        $payment = Payment::with('invoice')->findOrFail($this->paymentId);
        $invoice = $payment->invoice;
        $totalPayment = Payment::where('invoice_id', $invoice->id)->sum('amount_in_invoice_currency');
        $balance = $invoice->total - $totalPayment;
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
        $pdf = Pdf::loadView('pdf.Receipt', compact('receipt'));
        $path = "receipts/{$payment->reference_number}.pdf";
        Storage::disk('public')->put($path, $pdf->output());
        $payment->pdf_path = $path;
        $payment->save();
    }
}
