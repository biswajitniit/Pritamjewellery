<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metalissueentry extends Model
{
    protected $table = 'metalissueentries';
    protected $primaryKey = 'metal_issue_entries_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'metal_issue_entries_id',
        'metalissueentries_id',
        'metal_category',
        'location_id',
        'voucher_no',
        'metal_issue_entries_date',
        'karigar_id',
        'karigar_name',
        'metal_id',
        'purity_id',
        'converted_purity',
        'weight',
        'alloy_gm',
        'netweight_gm',
        'created_by',
        'updated_by',
    ];

    public function metal()
    {
        return $this->hasMany(Metal::class, 'metal_id', 'metal_id');
    }

    public function karigar()
    {
        return $this->hasMany(Karigar::class, 'id', 'karigar_id');
    }

    public function metalpurity()
    {
        return $this->hasMany(Metalpurity::class, 'purity_id', 'purity_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
