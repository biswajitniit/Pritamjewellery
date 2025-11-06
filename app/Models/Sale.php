<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'sold_on',
        'created_by',
        'updated_by',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    // connects with Metal/Stone/Misc. Models
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
