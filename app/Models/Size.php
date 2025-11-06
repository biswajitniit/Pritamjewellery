<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pcode;

class Size extends Model
{
    protected $table = 'sizes';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pcode_id',
        'schar',
        'item_name',
        'ssize',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function pcode()
    {
        return $this->belongsTo(Pcode::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'size_id');
    }
}
