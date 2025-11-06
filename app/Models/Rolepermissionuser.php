<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rolepermissionuser extends Model
{
    protected $table = 'rolepermissionusers';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'created_by',
        'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
