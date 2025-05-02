<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company;
        $name = preg_replace('/[-,.\']/', '', $name);
        $code = strtolower(preg_replace('/\s+/', '', $name));

        return [
            'code' => $code,
            'email' => $code . '@example.com',
            'name' => $name,
            'active' => fake()->randomElement([true, false]),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
