<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateQuoteJob;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Job;
use App\Models\Quote;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Resend\Laravel\Facades\Resend;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia::render('Quotes_Proposals/Quotes_Proposals', [
            'quotes' => Quote::OrderBy('created_at', 'DESC')
                ->where('user_id', Auth::id())
                ->with('client', 'job', 'items')
                ->get(),
        ]);
    }

    public function viewQuote(Quote $quote)
    {
        if ($quote->user_id === Auth::id()){
        return inertia::render('Quotes_Proposals/ViewQuote', [
            'quote' =>$quote->load('client', 'job.invoices.items', 'items'),
        ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuoteRequest $request)
    {
        $data = $request->validated();
        $latest = Quote::latest('id')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $random = mt_rand(100, 999);
        $quoteNumber = 'Q-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT) . '-' . $random;
        $business = Business::findOrFail($data['business_id']);
        $client   = Customer::findOrFail($data['client_id']);
        DB::beginTransaction();
        $quote = Quote::create([
            'user_id'     => auth()->id(),
            'business_id' => $data['business_id'],
            'client_id'   => $data['client_id'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'terms'       => $data['terms'],
            'exp_date'    => $data['exp_date'],
            'currency'    => $data['currency'],
            'tax'         => $data['tax'],
            'discount'    => $data['discount'],
            'subtotal'    => collect($data['items'])->sum(fn($item) => $item['quantity'] * $item['unit_price']),
            'total'       => collect($data['items'])->sum(fn($item) => $item['quantity'] * $item['unit_price'])
                + $data['tax']
                - $data['discount'],
            'quote_number' => $quoteNumber,
            'business_snapshot' =>json_encode([
                'name'  => $business->business_name,
                'email' => $business->business_email ?? null,
                'phone' => $business->business_phone ?? null,
            ]),
            'client_snapshot'   =>json_encode([
                'name'  => $client->name,
                'email' => $client->email ?? null,
                'phone' => $client->phone ?? null,
            ]),
        ]);
        foreach ($data['items'] as $item) {
            $quote->items()->create([
                'name'        => $item['name'],
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['quantity'] * $item['unit_price'],
            ]);
        }
        DB::commit();

        return response()->json([
            'message' => 'Quote created successfully',
            'quote'   => $quote->load('items'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote_Proposal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote_Proposal)
    {
        //
    }

    public function sendQuoteViaEmail(Request $request, $quote_number)
    {
        $quote = Quote::where('quote_number', $quote_number)->firstOrFail();

        $data = $request->validate([
            'subject' => 'required|string',
            'message' => 'required|string',
            'sendToEmail' => 'required|email',
            'replyToEmail' => 'required|email',
            'from_name' => 'required|string',
        ]);

        // Local filesystem path
        $storagePath = storage_path('app/public/quotes/quote-' . $quote->quote_number . '.pdf');

        // Public URL
        $attachmentUrl = asset('storage/quotes/quote-' . $quote->quote_number . '.pdf');

        // Check if PDF exists locally
        if (!file_exists($storagePath)) {
            // If not found, regenerate
            GenerateQuoteJob::dispatch($quote);

            return response()->json(['message' => 'Quote PDF not found, regeneration started'], 404);
        }

        // Send via Resend
        $response = Resend::emails()->send([
            'from' => $data['from_name'] .'<user@entroly.com.ng>',
            'to' => ['atuoisrael3@gmail.com'],
            'subject' => $data['subject'],
            'html' => nl2br($data['message']),
            'attachments' => [
                [
                    'filename' => 'quote-' . $quote->quote_number . '.pdf',
                    'path' => $attachmentUrl, // Resend expects a URL
                ],
            ],
        ]);

        return response()->json([
            'message' => 'Quote sent successfully',
            'resend_response' => $response,
        ]);
    }




    public function downloadQuote($quote_number)
    {
        $quote = Quote::where('quote_number', $quote_number)->firstOrFail();

        if ($quote->pdf_path && Storage::disk('public')->exists(str_replace('storage/', '', $quote->pdf_path))) {
            return response()->download(public_path($quote->pdf_path));
        }
        $path = dispatch_sync(new GenerateQuoteJob($quote));

        return response()->download($path);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        $data = $request->validated();
        $quote->update([
            'client_id' => $data['client_id'],
            'business_id' => $data['business_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'terms' => $data['terms'],
            'exp_date' => $data['exp_date'],
            'currency' => $data['currency'],
            'tax' => $data['tax'],
            'discount' => $data['discount'],
        ]);

        if (isset($data['items']) && is_array($data['items'])) {
            // Get IDs from the request
            $incomingIds = collect($data['items'])
                ->pluck('id')
                ->filter()
                ->all();

            // Delete items that are not in the request
            $quote->items()
                ->whereNotIn('id', $incomingIds)
                ->delete();

            foreach ($data['items'] as $itemData) {
                if (!empty($itemData['id'])) {
                    // Update existing
                    $quoteItem = $quote->items()->find($itemData['id']);
                    if ($quoteItem) {
                        $quoteItem->update([
                            'name' => $itemData['name'],
                            'description' => $itemData['description'],
                            'quantity' => $itemData['quantity'],
                            'unit_price' => $itemData['unit_price'],
                            'total' => $itemData['quantity'] * $itemData['unit_price'],
                        ]);
                    }
                } else {
                    // Create new
                    $quote->items()->create([
                        'name' => $itemData['name'],
                        'description' => $itemData['description'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total' => $itemData['quantity'] * $itemData['unit_price'],
                    ]);
                }
            }
        }


        return response()->json([
            'message' => 'Quote updated successfully',
            'quote' => $quote->load('items'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function updateQuoteStatus(Request $request, Quote $quote)
    {
        $data = $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
        ]);

        if ($quote->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        if ($data['status'] === 'accepted'){
        DB::beginTransaction();
            $job = Job::create([
                'user_id' => $quote->user_id,
                'quote_id' => $quote->id,
                'customer_id' => $quote->client_id,
                'business_id' => $quote->business_id,
                'job_title' => $quote->title,
                'description' => $quote->description,
                'status' => 'pending',
                'amount' => $quote->total,
                'start_date' => now(),
                'due_date' => $quote->exp_date,
            ]);

            $quote->update([
                'job_id' => $job->id,
                'status' => 'accepted',
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Quote converted to Job successfully',
                'job' => $job->load('invoices.items', 'customer'),
            ]);
        }else{
            $quote->update([
                'status' => $data['status'],
            ]);
            return response()->json([
                'message' => 'Quote status updated successfully',
                'status' => $quote->status,
            ]);
        }
    }


    public function destroy(Quote $quote)
    {
        if ($quote->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $quote->delete();
        return response()->json([
            'message' => 'Quote deleted successfully',
        ]);
    }

}
