<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEffect extends Model
{
    protected $table = 'stock_effects';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'vou_no',
        'metal_receive_entries_date',
        'location_name',
        'ledger_name',
        'ledger_code',
        'ledger_type',
        'metal_category',
        'metal_name',
        'net_wt',
        'purity',
        'pure_wt',
    ];
}