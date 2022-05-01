<?php

namespace Database\Factories;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "business_name" => $this->faker->name,
            "manager_full_name" => $this->faker->name(),
            "manager_phone" => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            "address" => $this->faker->address(),
            "is_active" => true,
            "bank_name" => $this->faker->randomElements(['zenith', 'fidelity', 'kuda'], $count = 1, $allowDuplicates = false)[0],
            "bank_account_number" => $this->faker->numerify('##########'),
            "bank_account_name" => $this->faker->name(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'location' => new Point($this->faker->latitude, $this->faker->longitude)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
