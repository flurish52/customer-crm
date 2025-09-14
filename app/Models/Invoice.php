<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'invoice_number',
        'user_id',
        'customer_id',
        'job_id',
        'customer_snapshot',
        'job_snapshot',
        'business_snapshot',
        'issue_date',
        'due_date',
        'status',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
