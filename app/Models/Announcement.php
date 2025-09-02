<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content', 
        'image',
        'action_url',
        'target_roles',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'target_roles' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created the announcement
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the announcement is targeted to a specific role
     */
    public function isTargetedToRole($roleId): bool
    {
        if (!$this->target_roles) {
            return true; // If no specific roles, show to everyone
        }
        
        return in_array($roleId, $this->target_roles);
    }

    /**
     * Check if the announcement is targeted to any of the user's roles
     */
    public function isTargetedToUser($user): bool
    {
        if (!$this->target_roles) {
            return true; // If no specific roles, show to everyone
        }
        
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return !empty(array_intersect($this->target_roles, $userRoleIds));
    }
}