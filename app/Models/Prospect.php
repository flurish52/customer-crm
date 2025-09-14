<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
    ];

    public function prospectActivities()
    {
        return $this->hasMany(ProspectActivityLog::class);
    }
}
