<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory()->create([
            'code' => 'shiftech',
            'email' => 'info@shiftech.my.id',
            'name' => 'Shiftech',
            'active' => 1,
        ]);
        Company::factory(10)->create();
    }
}
