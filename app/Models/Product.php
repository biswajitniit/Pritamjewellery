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
        'pattern_id',
        'size_id',
        'uom_id',
        'standard_wt',
        'kid',
        'lead_time_karigar',
        'product_lead_time',
        'stone_charge',
        'lab_charge',
        'loss',
        'purity',
        'item_pic',
        'kt',
        'pcodechar',
        'remarks',
        'bulk_upload',
        'customer_order',
        'karigar_loss',
        'created_by',
        'updated_by',
    ];



    // Relationships
    public function company()
    {
        return $this->belongsTo(Customer::class, 'company_id');
    }

    public function pcode()
    {
        return $this->belongsTo(Pcode::class, 'pcode_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function karigar()
    {
        return $this->belongsTo(Karigar::class, 'kid');
    }
}
