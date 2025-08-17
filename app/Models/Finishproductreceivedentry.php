<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finishproductreceivedentry extends Model
{
    protected $table = 'finishproductreceivedentries';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'karigar_id',
        'karigar_name',
        'bal',
        'voucher_date',
        'location_id',
        'voucher_no',
        'purity',
        'net_wt',
        'loss',
        'total_wt',
        'stone_wt',
        'created_by',
        'updated_by',
    ];
    public function karigar()
    {
        return $this->belongsTo(Karigar::class, "karigar_id", "id");
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
