<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customerordertempitem;
use App\Models\Customer;
use App\Models\Issuetokarigar;

class Customerordertemp extends Model
{
    protected $table = 'customerordertemps';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'jo_no',
        'customer_id',
        'jo_date',
        'vendor_site',
        'order_type',
        'type',
        'created_by',
        'updated_by',
    ];


    public function customerordertempitems()
    {
        //return $this->hasMany(Customerordertempitem::class);
        return $this->hasMany(Customerordertempitem::class, 'order_id', 'id');
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
