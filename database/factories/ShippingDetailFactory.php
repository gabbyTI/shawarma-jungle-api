<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address(),
            'landmark' => null,
            'description' => $this->faker->sentence(),
            'phone' => $this->faker->phoneNumber(),
            'second_phone' => null,
            'state' => $this->faker->word . ' state',
            'city' => $this->faker->word,
            'location' => [
                "latitude" => $this->faker->latitude,
                "longitude" => $this->faker->longitude
            ]
        ];
    }
}
