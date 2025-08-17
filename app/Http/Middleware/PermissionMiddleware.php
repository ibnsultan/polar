<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;
use App\Models\Permission;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission, ?string $scopes = null): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status'=>false, 'message' => 'Unauthorized'], 401);
            }
            abort(401, 'Unauthorized');
        }

        $scopesArray = $scopes ? explode('|', $scopes) : null;
        
        if (!$this->hasPermission($permission, $scopesArray)) {
            if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status'=>false, 'message' => 'You have no access to this resource'], 403);
            }
            abort(403, 'You have no access to this resource');
        }

        return $next($request);
    }

    /**
     * Check if the user has the required permission in specific scope(s)
     */
    public function hasPermission(string $permission, ?array $scopes = null): bool
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

    /**
     * Get the permission scope that the user has
     */
    public function getPermissionScope(string $permission): ?string
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
