<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $fillable = [
        'location_id',
        'vendor_id',
        'invoice_no',
        'purchase_on',
        'created_by',
        'updated_by',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }


    // connects with Metal/Stone/Misc. Models
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }


}
