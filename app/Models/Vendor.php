<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';

    protected $fillable = [
        'vid',
        'name',
        'vendor_code',
        'address',
        'city',
        'state',
        'phone',
        'mobile',
        'contact_person',
        'gstin',
        'statecode',
        'is_active',
        'created_by',
        'updated_by',
    ];


    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
