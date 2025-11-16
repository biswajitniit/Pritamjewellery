<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customerorder;

class Customerorderitem extends Model
{
    protected $table = 'customerorderitems';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'order_id',
        'sl_no',
        'item_code',
        'kid',
        'design',
        'description',
        'size',
        'finding',
        'uom',
        'kt',
        'std_wt',
        'conv_wt',
        'ord_qty',
        'ord_qty_actual',
        'total_wt',
        'lab_chg',
        'stone_chg',
        'add_l_chg',
        'total_value',
        'loss_percent',
        'min_wt',
        'max_wt',
        'ord',
        'delivery_date',
        'remarks'
    ];

    /**

     * Get the post that owns the comment.

     */
    // Each Customer Order Item belongs to one Customer Order
    public function customerorder()
    {
        return $this->belongsTo(Customerorder::class, 'order_id', 'id');
    }

    // Each Customer Order Item may have been issued to a karigar (linked by item_code)
    public function issuetokarigaritems()
    {
        return $this->hasMany(Issuetokarigaritem::class, 'item_code', 'item_code');
    }
}
