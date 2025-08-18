<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'scopes', 'module_id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'scopes' => 'json',         // ['none', 'owned', 'added', 'both', 'all']
    ];

    # belongs to module
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    # has many role permissions
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }
}