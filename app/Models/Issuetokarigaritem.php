<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Karigar;
use App\Models\Issuetokarigar;

class Issuetokarigaritem extends Model
{
    protected $table = 'issuetokarigaritems';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'job_no',
        'issue_to_karigar_id',
        'item_code',
        'design',
        'description',
        'size',
        'uom',
        'st_weight',
        'min_weight',
        'max_weight',
        'qty',
        'kid',
        'delivery_date',
        'finish_product_received',
    ];

    // public function karigar()
    // {
    //     return $this->belongsTo(Karigar::class);
    // }

    public function issuetokarigar()
    {
        return $this->belongsTo(Issuetokarigar::class, 'issue_to_karigar_id', 'id');
    }
}
