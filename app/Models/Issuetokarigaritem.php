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
        'financial_year_id',
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
        'bal_qty',
        'kid',
        'delivery_date',
        'finish_product_received',
        'quality_check',
    ];

    public function karigar()
    {
        return $this->belongsTo(Karigar::class, 'kid', 'kid');
    }

    // Each item belongs to one IssueToKarigar record
    public function issuetokarigar()
    {
        return $this->belongsTo(Issuetokarigar::class, 'issue_to_karigar_id', 'id');
    }

    // Each issued item is linked to one customer order item via item_code
    public function customerorderitem()
    {
        return $this->belongsTo(Customerorderitem::class, 'item_code', 'item_code');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }
}
