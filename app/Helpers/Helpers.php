<?php

use App\Models\RolePermission;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

if(!function_exists('user')) {
    function user()
    {
        return auth()->user();
    }
}

if(!function_exists('team')) {
    function team()
    {
        return user() ? user()->currentTeam : null;
    }
}

if(!function_exists('active')) {
    function active($string)
    {
        $currentUri = request()->path();

        // path is exactly the same as $string
        // starts with app/$string/ or admin/$string/
        if ($currentUri === $string || str_starts_with($currentUri, "app/$string") || str_starts_with($currentUri, "admin/$string")) {
            return true;
        }

        return false;
    }
}

if(!function_exists('is_admin')) {
    function is_admin()
    {
        return user() && can('access_admin_panel');
    }
}

if(!function_exists('can')) {
    /**
     * Check if the user has the required permission in specific scope(s)
     */
    function can(string $permission, ?array $scopes = null): bool
    {
        $user = Auth::user();
        
        if (!$user || !$user->role_name) {
            return false;
        }

        // Get the permission record
        $permissionRecord = Permission::where('name', $permission)->first();
        
        if (!$permissionRecord) {
            return false;
        }

        // Get the user's role permission for this permission
        $rolePermission = RolePermission::where('role_id', $user->role->id ?? null)
            ->where('permission_id', $permissionRecord->id)
            ->first();

        if (!$rolePermission) {
            return false;
        }

        // If no specific scopes are required, just check if permission exists
        if (!$scopes) {
            return true;
        }

        // Map scope names to their numeric values
        $scopeMap = [
            'none' => 0,
            'owned' => 1,
            'added' => 2,
            'both' => 3,
            'all' => 4
        ];

        // Check if the user's permission scope matches any of the required scopes
        foreach ($scopes as $requiredScope) {
            if (isset($scopeMap[$requiredScope]) && $rolePermission->scope == $scopeMap[$requiredScope]) {
                return true;
            }
        }

        return false;
    }
}

if(!function_exists('scope')) {
    /**
     * Get the permission scope that the user has
     */
    function scope(string $permission): ?string
    {
        $user = Auth::user();
        
        if (!$user || !$user->role) {
            return null;
        }

        // Get the permission record
        $permissionRecord = Permission::where('name', $permission)->first();
        
        if (!$permissionRecord) {
            return null;
        }

        // Get the user's role permission for this permission
        $rolePermission = RolePermission::where('role_id', $user->roles->id ?? null)
            ->where('permission_id', $permissionRecord->id)
            ->first();

        if (!$rolePermission) {
            return null;
        }

        // Map numeric values back to scope names
        $scopeMap = [
            0 => 'none',
            1 => 'owned',
            2 => 'added',
            3 => 'both',
            4 => 'all'
        ];

        return $scopeMap[$rolePermission->scope] ?? null;
    }
}