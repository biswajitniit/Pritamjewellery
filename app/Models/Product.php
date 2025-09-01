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
        'company_id',
        'vendorsite',
        'item_code',
        'design_num',
        'description',
        'pcode_id',
        'size_id',
        'uom_id',
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
        return $this->belongsTo(Karigar::class, 'kid'); // foreign key is kid
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'company_id'); // foreign key is company_id
    }

    public function pcode()
    {
        return $this->belongsTo(Pcode::class, 'pcode_id'); // foreign key is pcode_id
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id'); // foreign key is size_id
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id'); // foreign key is uom_id
    }

    public function saleItems()
    {
        return $this->morphMany(SaleItem::class, 'itemable');
    }
}
