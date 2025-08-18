<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';
    protected $fillable = ['role_id', 'permission_id', 'scope'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the role that owns the permission.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the permission associated with the role.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}