<?php

namespace App\Jobs;

use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateQuoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function handle(): void
    {
        $pdf = Pdf::loadView('pdf.Quote', [
            'quote' => $this->quote->load('job', 'client', 'items', 'business')
        ]);

        $fileName = 'quotes/quote-' . $this->quote->quote_number . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());

        $this->quote->update([
            'status' => $this->quote->status === 'draft' ? 'sent' : $this->quote->status,
            'pdf_path' => 'storage/' . $fileName,
        ]);
    }
}
