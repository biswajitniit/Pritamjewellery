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
        'financial_year_id',
        'location_id',
        'vou_no',
        'metal_receive_entries_date',
        'customer_id',
        'cust_name',
        'cust_address',
        'item_type',
        'item',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function metalpurity()
    {
        return $this->belongsTo(Metalpurity::class, 'purity_id', 'purity_id');
    }

    // 🔹 Conditional relationships for item_type
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

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }
}
