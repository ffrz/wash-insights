<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Technician::factory(10)->create();
    }
}
