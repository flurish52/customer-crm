<?php

namespace App\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoicePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invoiceId;

    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function handle()
    {
        $invoice = Invoice::with('items', 'payments')->findOrFail($this->invoiceId);
        $business = json_decode($invoice->business_snapshot);
        $customer = json_decode($invoice->customer_snapshot);
        $payments = $invoice->payments;
        $totalPaid = $payments->sum('amount_in_invoice_currency');
        $balance = $invoice->total - $totalPaid;
        $job = $invoice->job_snapshot ? json_decode($invoice->job_snapshot) : null;

        $pdf = Pdf::loadView('pdf.Invoice', [
            'invoice' => $invoice,
            'business' => $business,
            'customer' => $customer,
            'payments' => $payments,
            'job' => $job,
            'balance' => $balance,
            'totalPaid' => $totalPaid,
        ]);

        $pdfFolder = storage_path('app/public/invoices/pdfs');

        // Ensure folder exists
        if (!file_exists($pdfFolder)) {
            mkdir($pdfFolder, 0755, true);
        }

        $pdfPath = $pdfFolder . '/invoice-' . $invoice->invoice_number . '.pdf';
        $pdf->save($pdfPath);

        // Save relative path to DB
        $invoice->pdf_path = 'invoices/pdfs/invoice-' . $invoice->invoice_number . '.pdf';
        $invoice->save();

        return response()->download($pdfPath);
    }

}
