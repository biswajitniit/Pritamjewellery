<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stone extends Model
{
    protected $table = 'stones';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'additional_charge_id',
        'category',
        'description',
        'uom',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user that owns the productstonedetails.
     */
    public function productstonedetails()
    {
        return $this->hasMany(Productstonedetails::class);
    }


    public function purchaseItems()
    {
        return $this->morphMany(PurchaseItem::class, 'itemable');
    }


    public function saleItems()
    {
        return $this->morphMany(SaleItem::class, 'itemable');
    }
}
