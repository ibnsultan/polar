<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    # has users by role:name - users:role columns
    public function users()
    {
        return $this->hasMany(User::class, 'role_name', 'name');
    }

    # has many permissions
    public function permissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}