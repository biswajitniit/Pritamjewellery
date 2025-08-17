<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productstonedetails extends Model
{
    protected $table = 'productstonedetails';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'product_id',
        'stone_id',
        'category',
        'pcs',
        'weight',
        'rate',
        'amount',
        'created_by',
        'updated_by',
    ];



    public function product()
    {
        return $this->hasOne(Metal::class, 'product_id', 'id');
    }

    public function stone()
    {
        return $this->hasMany(Stone::class, 'id', 'stone_id');
    }
}
