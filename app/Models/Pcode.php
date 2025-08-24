<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Size;

class Pcode extends Model
{
    protected $table = 'pcodes';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function size()
    {
        return $this->hasMany(Size::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'pcode_id');
    }
}
