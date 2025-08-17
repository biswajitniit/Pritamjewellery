<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finishedproductpdi extends Model
{
    protected $table = 'finishedproductpdis';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'jo_number',
        'item_code',
        'qty',
        'uom',
        'size',
        'net_wt',
        'purity',
        'rate',
        'a_lab',
        'stone_chg',
        'loss',
        'kid',
        'delivered_stock_out',
    ];
}
