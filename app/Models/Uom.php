<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    protected $table = 'uoms';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uomid',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'uom_id');
    }
}
