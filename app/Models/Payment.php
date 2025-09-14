<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'user_id',
        'customer_id',
        'job_id', 'invoice_id',
        'amount_in_business_currency',
        'amount_in_invoice_currency',
        'exchange_rate', 'method',
        'paid_at',
        'notes',
        'reference_number'
    ];


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    // Helper: Scope for payments by method
    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    // Helper: Scope for payments in a date range
    public function scopeInDateRange($query, $start, $end)
    {
        return $query->whereBetween('paid_at', [$start, $end]);
    }

    // Helper: Check if payment is linked to an invoice
    public function hasInvoice()
    {
        return $this->invoice_id !== null;
    }

    // Helper: Get payment summary (job + invoice + customer)
    public function summary()
    {
        return [
            'invoice' => $this->invoice?->id,
            'job' => $this->job?->title,
            'customer' => $this->customer?->name,
            'amount' => $this->formatted_amount,
            'method' => $this->method,
            'paid_at' => $this->paid_at,
        ];
    }
}
