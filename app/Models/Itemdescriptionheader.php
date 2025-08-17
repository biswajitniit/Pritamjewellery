<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Itemdescriptionheader extends Model
{
    protected $table = 'itemdescriptionheaders';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'itemdescriptionheaders_id',
        'company_id',
        'number_of_digits',
        'value',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'company_id');
    }
}
