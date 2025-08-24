<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $table = 'patterns';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pid',
        'pat_desc',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // public function products()
    // {
    //     return $this->hasMany(Product::class, 'pattern_id');
    // }
}
