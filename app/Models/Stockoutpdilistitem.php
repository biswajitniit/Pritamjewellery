<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stockoutpdilistitem extends Model
{
    protected $table = 'stockoutpdilistitems';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'stockoutpdilist_id',
        'finishedproductpdis_id',
        'purity',
        'qty',
        'net_weight',
        'stone_chg',
        'lab_chg',
        'add_lab_chg',
        'amount'
    ];

    /**
     * Get the user that owns the productstonedetails.
     */
    public function stockoutpdilists()
    {
        return $this->hasMany(Stockoutpdilist::class, 'id', 'stockoutpdilist_id');
    }

    public function finishedproductpdis()
    {
        return $this->hasMany(Finishedproductpdi::class, 'id', 'finishedproductpdis_id');
    }
}
