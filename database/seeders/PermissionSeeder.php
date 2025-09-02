<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            ['name' => 'access_admin_panel', 'module_id' => 1, 'scopes' => json_encode(['none', 'all'])],
            ['name' => 'view_announcements', 'module_id' => 2, 'scopes' => json_encode(['none', 'all'])],
            ['name' => 'create_announcements', 'module_id' => 2, 'scopes' => json_encode(['none', 'all'])],
            ['name' => 'update_announcements', 'module_id' => 2, 'scopes' => json_encode(['none', 'owned', 'all'])],
            ['name' => 'delete_announcements', 'module_id' => 2, 'scopes' => json_encode(['none', 'owned', 'all'])],
        ]);

        // seed role permissions
        $adminId = Role::where('name', 'admin')->first()->id;
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            RolePermission::create([
                'role_id' => $adminId,
                'permission_id' => $permission->id,
                'scope' => 4,   # 4 is the scope for full access
            ]);
        }
    }
}
