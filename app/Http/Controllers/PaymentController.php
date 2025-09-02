<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePaymentRequest $request)
    {
        $invoice = Invoice::with('job', 'customer')
            ->where('id', $request->invoiceId)
            ->first();
        if ($invoice->user_id !== Auth::id()) return;
        try {
            DB::beginTransaction();
           Payment::create([
                'user_id' => Auth::id(),
                'invoice_id' => $request->invoiceId,
                'customer_id' => $invoice->customer->id,
                'job_id' => $invoice->job->id,
                'amount' => $request->amount,
                'paid_at' => $request->date,
                'method' => $request->payment_method,
                'notes' => $request->notes ?? null,
            ]);
           $totalPayment = Payment::where('invoice_id', $request->invoiceId)->sum('amount');
            $balance = $invoice->total - $totalPayment;

            if ($balance <= 0) {
                $invoice->update(['status' => 'paid']);
            } elseif ($balance > 0 && $invoice->due_date >= now()) {
                $invoice->update(['status' => 'partially_paid']);
            } elseif ($balance > 0 && $invoice->due_date < now()) {
                $invoice->update(['status' => 'overdue']);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Payment recorded successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e
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
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
