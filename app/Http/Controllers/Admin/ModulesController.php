<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Module;
use App\Models\Permission;

class ModulesController extends Controller
{
    
    public function index()
    {
        $modules = Module::withCount('permissions')->get();
        return view('admin.modules.index', compact('modules'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:modules,name',
            'description' => 'required|string|max:320'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $module = Module::create($request->only(['name', 'description']));

        return response()->json([
            'status' => true,
            'message' => 'Module created successfully',
            'redirect' => route('admin.modules.list')
        ]);
    }

    public function update(Request $request, Module $module)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:modules,name,' . $module->id,
            'description' => 'required|string|max:320'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $module->update($request->only(['name', 'description']));

        return response()->json([
            'status' => true,
            'message' => 'Module updated successfully',
            'redirect' => route('admin.modules.list')
        ]);
    }

    public function toggle(Module $module)
    {
        // core modules cannot be deactivated
        if ($module->is_core && !$module->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Core modules cannot be deactivated'
            ]);
        }

        // Toggle the is_active status
        $module->is_active = !$module->is_active;
        $module->save();

        return response()->json([
            'status' => true,
            'message' => 'Module status updated successfully',
            'redirect' => route('admin.modules.list')
        ]);
    }

    public function destroy(Module $module)
    {
        // Check if module has permissions
        if ($module->permissions()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete module that has permissions assigned'
            ]);
        }

        $module->delete();

        return response()->json([
            'status' => true,
            'message' => 'Module deleted successfully',
            'redirect' => route('admin.modules.list')
        ]);
    }

    public static function routes(){
        Route::get('', [self::class, 'index'])->name('admin.modules.list');
        Route::get('toggle/{module}', [self::class, 'toggle'])->name('admin.modules.toggle');

        Route::post('', [self::class, 'store'])->name('admin.modules.store');
        Route::put('{module}', [self::class, 'update'])->name('admin.modules.update');
        Route::delete('{module}', [self::class, 'destroy'])->name('admin.modules.destroy');
    }
}