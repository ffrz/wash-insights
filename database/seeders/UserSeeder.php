<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserFactory::$defaultPassword = Hash::make('12345');
        User::factory()->create([
            'company_id' => 1,
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@shiftech.my.id',
            'role' => User::Role_Admin,
            'active' => 1,
        ]);
        User::factory()->create([
            'company_id' => 1,
            'username' => 'fahmi',
            'name' => 'Fahmi',
            'email' => 'fahmi@shiftech.my.id',
            'role' => User::Role_Technician,
            'active' => 1,
        ]);
        User::factory(100)->create();
    }
}
