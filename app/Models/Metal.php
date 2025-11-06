<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metal extends Model
{
    protected $table = 'metals';
    protected $primaryKey = 'metal_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'metal_id',
        'metal_name',
        'metal_category',
        'metal_hsn',
        'metal_sac',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function metalpurity()
    {
        return $this->belongsTo(Metalpurity::class);
    }

    public function metalreceiveentry()
    {
        return $this->belongsTo(Metalreceiveentry::class);
    }



    public function purchaseItems()
    {
        return $this->morphMany(PurchaseItem::class, 'itemable');
    }


    public function saleItems()
    {
        return $this->morphMany(SaleItem::class, 'itemable');
    }

    // 🔹 One Metal can have many issue entries
    public function metalissueentries()
    {
        return $this->hasMany(Metalissueentry::class, 'metal_id', 'metal_id');
    }
}
