<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualitycheckitem extends Model
{
    protected $table = 'qualitycheckitems';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'qualitychecks_id',
        'karigar_id',
        'job_no',
        'item_code',
        'design',
        'description',
        'purity',
        'size',
        'uom',
        'order_qty',
        'receive_qty',
        'bal_qty',
        'net_wt',
        'rate',
        'a_lab',
        'stone_chg',
        'loss',
        'gross_wt_items',
        'design_items',
        'solder_items',
        'polish_items',
        'finish_items',
        'mina_items',
        'other_items',
        'remark_items',
        'pdi_list',
        'status',
        'created_by',
        'updated_by',
    ];

    public function qualitycheck()
    {
        return $this->belongsTo(Qualitycheck::class, 'qualitychecks_id');
    }

    public function karigar()
    {
        return $this->belongsTo(Karigar::class, 'karigar_id', 'id');
    }
}
