<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = 'locations';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'location_name',
        'location_address',
        'created_by',
        'updated_by',
    ];

    // 🔹 One Location can have many Metal Issue Entries
    public function metalissueentries()
    {
        return $this->hasMany(Metalissueentry::class, 'location_id', 'id');
    }

    public function finishedProductPdis()
    {
        return $this->hasMany(FinishedProductPdi::class, 'location_id');
    }

    public function qualitychecks()
    {
        return $this->hasMany(Qualitycheck::class, 'location_id');
    }
}
