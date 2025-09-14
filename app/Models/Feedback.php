<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /** @use HasFactory<\Database\Factories\FeedbackFactory> */
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'user_id',
        'modules',
        'features',
        'frequency',
        'navigation',
        'speed',
        'confusing',
        'missing_features',
        'satisfaction',
        'recommend',
        'suggestions',
        'business_type',
        'team_size',
    ];

    protected $casts = [
        'modules' => 'array',
        'features' => 'array',
    ];

}
