<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Permission;
use App\Models\Module;
use App\Models\Role;
use App\Models\RolePermission;

class PermissionsController extends Controller
{
    
    public function index()
    {
        $permissions = Permission::with('module')->get();
        $modules = Module::all();
        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|unique:permissions,name',
            'module_id' => 'required|exists:modules,id',
            'scopes' => 'required|array|min:1',
            'scopes.*' => 'in:none,owned,added,both,all'
        ]);

        if ($validator->fails()) {
            return $this->jsonError($validator->errors()->first());
        }

        // Convert name to snake_case
        $name = Str::snake(strtolower($request->name));

        $permission = Permission::create([
            'name' => $name,
            'module_id' => $request->module_id,
            'scopes' => $request->scopes
        ]);

        if( !$permission) 
            return $this->jsonError('Failed to create permission');

        // Allocate permission to admin role
        $adminRole = Role::where('name', 'admin')->first();
        RolePermission::create([
            'role_id' => $adminRole->id,
            'permission_id' => $permission->id,
            'scope' => 4,   // 4 is the scope for full access
        ]);

        $this->redirect = route('admin.permissions.index');
        return $this->jsonSuccess('Permission created successfully');
    }

    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:120|unique:permissions,name,' . $permission->id,
            'module_id' => 'required|exists:modules,id',
            'scopes' => 'required|array|min:1',
            'scopes.*' => 'in:none,owned,added,both,all'
        ]);

        if ($validator->fails()) {
            return $this->jsonError($validator->errors()->first());
        }

        // Convert name to snake_case
        $name = Str::snake(strtolower($request->name));

        $permission->update([
            'name' => $name,
            'module_id' => $request->module_id,
            'scopes' => $request->scopes
        ]);

        $this->redirect = route('admin.permissions.index');
        return $this->jsonSuccess('Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission has role assignments
        if ($permission->rolePermissions()->count() > 0) {
            return $this->jsonError('Cannot delete permission that is assigned to roles');
        }

        $permission->delete();

        $this->redirect = route('admin.permissions.index');
        return $this->jsonSuccess('Permission deleted successfully');
    }

    public static function routes(){
        Route::get('', [self::class, 'index'])->name('admin.permissions.index');
        Route::post('', [self::class, 'store'])->name('admin.permissions.store');
        Route::put('{permission}', [self::class, 'update'])->name('admin.permissions.update');
        Route::delete('{permission}', [self::class, 'destroy'])->name('admin.permissions.destroy');
    }
}
