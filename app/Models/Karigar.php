<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Issuetokarigaritem;

class Karigar extends Model
{
    protected $table = 'karigars';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kid',
        'kname',
        'kfather',
        'address',
        'phone',
        'mobile',
        'pan',
        'remark',
        'introducer',
        'gstin',
        'statecode',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function issuetokarigaritem()
    {
        return $this->hasMany(Issuetokarigaritem::class);
    }

    public function metalissueentry()
    {
        return $this->belongsTo(Metalissueentry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
