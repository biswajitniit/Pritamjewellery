<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metalpurity extends Model
{
    protected $table = 'metalpurities';
    protected $primaryKey = 'purity_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'purity_id',
        'metal_id',
        'purity',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the phone record associated with the productstonedetails.
     */
    public function metal()
    {
        return $this->hasMany(Metal::class, 'metal_id', 'metal_id');
    }

    public function metalreceiveentry()
    {
        return $this->belongsTo(Metalreceiveentry::class);
    }

    // 🔹 One Metal Purity can be used in many issue entries
    public function metalissueentries()
    {
        return $this->hasMany(Metalissueentry::class, 'purity_id', 'purity_id');
    }
}
