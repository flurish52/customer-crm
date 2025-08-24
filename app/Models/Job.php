<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\In;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $table = 'customer_jobs';

    protected $fillable = [
        'customer_id',
        'user_id',
        'business_id',
        'job_title',
        'description',
        'status',
        'amount',
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
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

}
