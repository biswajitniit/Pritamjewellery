<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id',
        'itemable_type',
        'itemable_id',
        'purity_id',
        'hsn',
        'quantity',
        'rate',
        'subtotal_amount',
        'gstin_percent',
        'gstin_amount',
        'total_amount',
    ];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function purity()
    {
        return $this->belongsTo(Metalpurity::class, 'purity_id');
    }
}
