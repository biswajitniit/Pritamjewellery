<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    protected $fillable = [
        'applicable_year',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by'
    ];

    public function voucherTypes()
    {
        return $this->hasMany(VoucherType::class, 'financial_year_id');
    }

    public function metalReceiveEntries()
    {
        return $this->hasMany(Metalreceiveentry::class, 'financial_year_id', 'id');
    }

    public function issueToKarigarItems()
    {
        return $this->hasMany(Issuetokarigaritem::class, 'financial_year_id', 'id');
    }

    public function finishProductReceivedEntryItems()
    {
        return $this->hasMany(Finishproductreceivedentryitem::class, 'financial_year_id', 'id');
    }
}
