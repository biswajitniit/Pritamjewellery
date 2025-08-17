<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualitycheck extends Model
{
    protected $table = 'qualitychecks';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'karigar_id',
        'karigar_name',
        'type',
        'location_id',
        'qc_voucher',
        'qualitycheck_date',
        'item_code',
        'job_no',
        'design',
        'description',
        'purity',
        'size',
        'uom',
        'order_qty',
        'receive_qty',
        'bal_qty',
        'created_by',
        'updated_by',
    ];

    public function karigar()
    {
        return $this->hasMany(Karigar::class, 'id', 'karigar_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
