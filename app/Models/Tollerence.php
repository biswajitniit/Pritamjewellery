<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tollerence extends Model
{
    protected $table = 'tollerences';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'weight_min',
        'weight_max',
        'tolerance_plus',
        'tolerance_minus',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
