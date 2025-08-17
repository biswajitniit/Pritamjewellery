<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'vendorsite',
        'company_id',
        'item_code',
        'design_num',
        'description',
        'pattern',
        'size',
        'uom',
        'standard_wt',
        'kid',
        'lead_time_karigar',
        'product_lead_time',
        'stone_charge',
        'lab_charge',
        'additional_lab_charges',
        'loss',
        'purity',
        'item_pic',
        'stone_wt',
        'st_charge',
        'kt',
        'kundan',
        'remarks',
        'bulk_upload',
        'customer_order',
        'karigar_loss',
        'created_by',
        'updated_by',
    ];

    public function karigar()
    {
        return $this->hasMany(Karigar::class, 'id', 'kid');
    }
}
