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
            'notes' => 'Biaya Tagihan Listrik',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'PDAM',
            'notes' => 'Biaya Tagihan PDAM',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Internet',
            'notes' => 'Biaya Tagihan Internet',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Pulsa dan Quota',
            'notes' => 'Biaya pulsa dan Quota',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'ATK',
            'notes' => 'Biaya ATK',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Bahan Servis',
            'notes' => 'Biaya bahan-bahan servis habis pakai',
        ]);

        OperationalCostCategory::factory()->create([
            'name' => 'Alat Servis',
            'notes' => 'Biaya alat-alat servis non bahan',
        ]);
    }
}
