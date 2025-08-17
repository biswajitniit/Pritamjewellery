<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metalreceiveentry extends Model
{
    protected $table = 'metalreceiveentries';
    protected $primaryKey = 'metal_receive_entries_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'metal_receive_entries_id',
        'metalreceiveentries_id',
        'location_id',
        'vou_no',
        'metal_receive_entries_date',
        'customer_id',
        'cust_name',
        'cust_address',
        'metal_id',
        'purity_id',
        'weight',
        'issue_qty',
        'balance_qty',
        'last_entry_issue_date',
        'last_entry_issue_by',
        'dv_no',
        'dv_date',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function metal()
    {
        return $this->hasMany(Metal::class, 'metal_id', 'metal_id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'id', 'customer_id');
    }

    public function metalpurity()
    {
        return $this->hasMany(Metalpurity::class, 'purity_id', 'purity_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
