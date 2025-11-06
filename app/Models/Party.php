<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = ['party_type', 'party_code', 'name', 'address'];

    public function metalReceiveEntries()
    {
        return $this->hasMany(Metalreceiveentry::class, 'party_id');
    }
}
