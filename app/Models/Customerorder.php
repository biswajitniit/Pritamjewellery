<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customerorderitem;
use App\Models\Customer;
use App\Models\Issuetokarigar;

class Customerorder extends Model
{
    protected $table = 'customerorders';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'jo_no',
        'customer_id',
        'jo_date',
        'order_type',
        'type',
        'created_by',
        'updated_by',
    ];


    public function customerorderitems()
    {
        return $this->hasMany(Customerorderitem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function issuetokarigar()
    {
        return $this->belongsTo(Issuetokarigar::class);
    }
}
