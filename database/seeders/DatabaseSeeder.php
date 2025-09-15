<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Roles
        Role::insert([
            [
                'name' => 'admin',
                'description' => 'Administrator role with full permissions',
            ],
            [
                'name' => 'user',
                'description' => 'Basic user role with limited permissions',
            ],
        ]);

        User::factory()->withPersonalTeam()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'role_name' => 'admin',
        ]);

        // register other seeders
        $this->call([
            ModuleSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
