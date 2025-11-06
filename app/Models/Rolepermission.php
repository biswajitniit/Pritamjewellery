<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rolepermission extends Model
{
    protected $table = 'rolepermissions';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'rolepermissionuser_id',
        'user_id',
        'menu_name',
        'menu_permissions',
        'permissions_add',
        'permissions_edit',
        'permissions_delete',
        'permissions_view',
        'permissions_print',
    ];

    public function rolepermissionuser()
    {
        return $this->belongsTo(Rolepermissionuser::class, 'rolepermissionuser_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
