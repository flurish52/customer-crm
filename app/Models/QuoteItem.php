<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model
{
    /** @use HasFactory<\Database\Factories\QuoteItemFactory> */
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'name',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
