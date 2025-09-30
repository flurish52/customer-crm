<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    /** @use HasFactory<\Database\Factories\QuoteProposalFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'quotes';

    protected $fillable = [
        'user_id',
        'client_id',
        'job_id',
        'quote_number',
        'title',
        'description',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'business_id',
        'terms',
        'status',
        'due_date',
        'business_snapshot',
        'client_snapshot',
        'pdf_path'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
