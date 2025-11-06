<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cid',
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
}
