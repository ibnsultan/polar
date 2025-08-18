<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Role;
use App\Models\Module;
use App\Models\Permission;
use App\Models\RolePermission;

class RolesController extends Controller
{
    
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $role = Role::create($request->only(['name', 'description']));

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully',
            'redirect' => route('admin.roles.index')
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $role->update($request->only(['name', 'description']));

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully',
            'redirect' => route('admin.roles.index')
        ]);
    }

    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete role that has users assigned'
            ]);
        }

        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully',
            'redirect' => route('admin.roles.index')
        ]);
    }

    public function permissions(Role $role)
    {
        $this->modules = Module::with(['permissions' => function($query) use ($role) {
            $query->with(['rolePermissions' => function($q) use ($role) {
                $q->where('role_id', $role->id);
            }]);
        }])->get();

        $this->role = $role;
        return $this->jsonSuccess("Roles fetched successfully");
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permission_id' => 'required|exists:permissions,id',
            'scope' => 'required|integer|min:0|max:4'
        ]);

        if ($validator->fails()) {
            $this->jsonError($validator->errors()->first());
        }

        $permissionId = $request->input('permission_id');
        $scope = $request->input('scope');

        // no need to check if permission exists, as it's validated above
        // if scope is 0, delete the permission
        if ($scope == 0) {
            RolePermission::where('role_id', $role->id)
                ->where('permission_id', $permissionId)
                ->delete();
        } else {
            // update or create the permission with the new scope
            RolePermission::updateOrCreate(
                ['role_id' => $role->id, 'permission_id' => $permissionId],
                ['scope' => $scope]
            );
        }

        return $this->jsonSuccess("Permissions updated successfully");
    }

    public static function routes(){
        Route::get('', [self::class, 'index'])->name('admin.roles.index');
        Route::post('', [self::class, 'store'])->name('admin.roles.store');
        Route::put('{role}', [self::class, 'update'])->name('admin.roles.update');
        Route::delete('{role}', [self::class, 'destroy'])->name('admin.roles.destroy');
        Route::get('{role}/permissions', [self::class, 'permissions'])->name('admin.roles.permissions');
        Route::post('{role}/permissions', [self::class, 'updatePermissions'])->name('admin.roles.permissions.update');
    }
}
