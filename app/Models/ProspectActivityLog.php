<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProspectActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ProspectActivityLogFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'prospect_id',
        'user_id',
        'type',
        'content',
        'activity_date',
    ];

    public function prospect()
    {
        return $this->belongsTo(Prospect::class);
    }
}
