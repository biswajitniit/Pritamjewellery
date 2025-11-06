<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customerorder;
use App\Models\Issuetokarigar;

class Customer extends Model
{
    protected $table = 'customers';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cid',
        'party_type',
        'cust_name',
        'cust_code',
        'address',
        'city',
        'state',
        'phone',
        'mobile',
        'cont_person',
        'gstin',
        'statecode',
        'is_validation',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function customerorder()
    {
        return $this->hasMany(Customerorder::class);
    }

    public function issuetokarigar()
    {
        return $this->belongsTo(Issuetokarigar::class);
    }

    public function metalreceiveentry()
    {
        return $this->belongsTo(Metalreceiveentry::class);
    }


    public function products()
    {
        return $this->hasMany(Product::class, 'company_id');
    }

    // 🔹 One Customer can have many Metal Issue Entries
    public function metalissueentries()
    {
        return $this->hasMany(Metalissueentry::class, 'customer_id', 'id');
    }
}
