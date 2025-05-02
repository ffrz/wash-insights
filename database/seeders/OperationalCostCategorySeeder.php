<?php

namespace Database\Seeders;

use App\Models\OperationalCostCategory;
use Illuminate\Database\Seeder;

class OperationalCostCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OperationalCostCategory::factory()->create([
            'name' => 'Listrik',
            'description' => 'Biaya Tagihan Listrik',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'PDAM',
            'description' => 'Biaya Tagihan PDAM',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Internet',
            'description' => 'Biaya Tagihan Internet',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Pulsa dan Quota',
            'description' => 'Biaya pulsa dan Quota',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'ATK',
            'description' => 'Biaya ATK',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Bahan Servis',
            'description' => 'Biaya bahan-bahan servis habis pakai',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Alat Servis',
            'description' => 'Biaya alat-alat servis non bahan',
        ]);
    }
}
