<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metalissueentry extends Model
{
    protected $table = 'metalissueentries';
    protected $primaryKey = 'metal_issue_entries_id';

    protected $fillable = [
        'metal_issue_entries_id',
        'metalissueentries_id',
        //'metal_category',
        'location_id',
        'customer_id',
        'voucher_no',
        'metal_issue_entries_date',
        'karigar_id',
        'karigar_name',
        //'metal_id',
        'item_type',
        'item',
        'purity_id',
        'converted_purity',
        'weight',
        'alloy_gm',
        'netweight_gm',
        'created_by',
        'updated_by',
    ];



    public function metal()
    {
        return $this->belongsTo(Metal::class, 'item', 'metal_id');
    }

    public function stone()
    {
        return $this->belongsTo(Stone::class, 'item', 'id');
    }

    public function miscellaneous()
    {
        return $this->belongsTo(Miscellaneous::class, 'item', 'id');
    }

    // 🔹 Belongs to a Karigar
    public function karigar()
    {
        return $this->belongsTo(Karigar::class, 'karigar_id', 'id');
    }

    // 🔹 Belongs to a Metal Purity
    public function metalpurity()
    {
        return $this->belongsTo(Metalpurity::class, 'purity_id', 'purity_id');
    }

    // 🔹 Belongs to a Location
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    // 🔹 Belongs to a Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
