<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vouchertype extends Model
{
    protected $table = 'vouchertypes';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'location_id',
        'voucher_type',
        'applicable_year',
        'applicable_date',
        'startno',
        'prefix',
        'suffix',
        'status',
        'lastno',
        'created_by',
        'updated_by',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
