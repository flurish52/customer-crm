<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    protected $table = 'customer_jobs';

    protected $fillable = [
        'customer_id',
        'user_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

}
