<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\In;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'customer_jobs';

    protected $fillable = [
        'customer_id',
        'user_id',
        'quote_id',
        'business_id',
        'job_title',
        'description',
        'status',
        'amount',
        'start_date',
        'due_date',
        'completed_at',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    public function activities()
    {
        return $this->hasMany(ActivityWithClient::class, 'job_id');
    }

    public function quote()
    {
        return $this->hasOne(Quote::class, 'job_id');
    }

}
