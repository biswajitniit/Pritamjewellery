<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Miscellaneous extends Model
{
    use SoftDeletes;

    protected $table = 'miscellaneouses';

    protected $fillable = [
        'product_code',
        'product_name',
        'uom',
        'size',
        'is_active',
        'created_by',
        'updated_by',
    ];


    public function purchaseItems()
    {
        return $this->morphMany(PurchaseItem::class, 'itemable');
    }


    public function saleItems()
    {
        return $this->morphMany(SaleItem::class, 'itemable');
    }
}
