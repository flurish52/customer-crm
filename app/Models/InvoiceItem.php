<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceItemFactory> */
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'invoice_id',
        'description',
        'quantity',
        'unit_price',
        'total'
    ];


    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
