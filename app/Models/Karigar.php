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
        'karigar_loss',
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

    // 🔹 One Karigar can have many Metal Issue Entries
    public function metalissueentries()
    {
        return $this->hasMany(Metalissueentry::class, 'karigar_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'kid');
    }

    public function items()
    {
        return $this->hasMany(IssueToKarigarItem::class, 'kid', 'kid');
    }

    public function finishproductreceivedentries()
    {
        return $this->hasMany(Finishproductreceivedentry::class, 'karigar_id', 'id');
    }

    public function qualitycheckitems()
    {
        return $this->hasMany(Qualitycheckitem::class, 'karigar_id', 'id');
    }
}
