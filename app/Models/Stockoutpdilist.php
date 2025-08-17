<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stockoutpdilist extends Model
{
    protected $table = 'stockoutpdilists';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'customer_id',
        'customer_address',
        'dc_ref_no',
        'dc_date',
        'purity',
        'stock_out',
    ];

    /**
     * Get the user that owns the productstonedetails.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class, 'id', 'customer_id');
    }
}
