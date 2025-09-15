<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Module;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::insert([
            [
                'id' => 1,
                'name' => 'Core',
                'description' => 'Core module for basic functionalities',
            ],
            [
                'id' => 2,
                'name' => 'Announcements',
                'description' => 'Announcement management module',
            ]
        ]);
    }
}
