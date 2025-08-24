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
        'purity',
        'hsn',
        'quantity',
        'price',
        'subtotal_amount',
        'gstin_percent',
        'gstin_amount',
        'total_amount',
    ];

    public function itemable()
    {
        return $this->morphTo();
    }
}
