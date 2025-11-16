<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customerorder;
use App\Models\Issuetokarigaritem;

class Issuetokarigar extends Model
{
    protected $table = 'issuetokarigars';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'order_id',
        'created_by',
        'updated_by',
    ];

    // Each IssueToKarigar belongs to one customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    // Each IssueToKarigar is linked to one Customer Order
    public function customerorder()
    {
        return $this->belongsTo(Customerorder::class, 'order_id', 'id');
    }

    // Each IssueToKarigar has multiple issued items
    public function issuetokarigaritems()
    {
        return $this->hasMany(Issuetokarigaritem::class, 'issue_to_karigar_id', 'id');
    }
}
