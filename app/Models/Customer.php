<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'address',
        'note',
        'avatar',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, );
    }

}
