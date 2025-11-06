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

    public function customer()
    {
        return $this->hasMany(Customer::class, "id", "customer_id");
    }

    public function customerorder()
    {
        return $this->hasOne(Customerorder::class, 'id', 'order_id');
    }

    // public function issuetokarigaritem()
    // {
    //     return $this->hasMany(Issuetokarigaritem::class);
    // }


}
