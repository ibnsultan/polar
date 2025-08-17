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

        // disable foreign key checks to avoid issues with existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // truncate tables to start fresh
        User::truncate();
        Role::truncate();

        // enable foreign key checks again
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
