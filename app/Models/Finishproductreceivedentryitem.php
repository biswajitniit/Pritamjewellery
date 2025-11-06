<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finishproductreceivedentryitem extends Model
{
    protected $table = 'finishproductreceivedentryitems';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fprentries_id',
        'financial_year_id',
        'barcode',
        'job_no',
        'item_code',
        'design',
        'voucher_date',
        'description',
        'size',
        'uom',
        'qty',
        'receive_qty_from_karigar',
        'purity',
        'gross_wt',
        'st_weight',
        'k_excess',
        'mina',
        'loss_percentage',
        'loss_wt',
        'pure',
        'net',
        'deliverery_status',
        'created_by',
        'updated_by',
    ];

    public function finishproductreceivedentries()
    {
        return $this->belongsTo(Finishproductreceivedentry::class, 'fprentries_id', 'id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }
}
