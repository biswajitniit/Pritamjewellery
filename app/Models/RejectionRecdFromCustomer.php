<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RejectionRecdFromCustomer extends Model
{
    protected $table = 'rejection_recd_from_customers';

    protected $fillable = [
        'location_id',
        'vou_no',
        'job_no',
        'item_code',
        'kid',
        'qty',
        'gross_wt',
        'net_wt',
        'reason',
        'rej_lab_chg',
        'rej_st_chg',
        'rej_add_lab',
        'created_by',
        'updated_by',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
}
