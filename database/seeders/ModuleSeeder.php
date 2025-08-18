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

        // disable foreign key checks to avoid issues with existing data
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // truncate tables to start fresh
        Module::truncate();

        // enable foreign key checks again
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Module::insert([
            [
                'id' => 1,
                'name' => 'Core',
                'description' => 'Core module for basic functionalities',
            ]
        ]);
    }
}
