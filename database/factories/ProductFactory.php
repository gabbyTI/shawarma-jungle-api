<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productName = $this->faker->randomElements(['chicken', 'beef', 'chicken&beef'], $count = 1, $allowDuplicates = false)[0] . ' shawarma';

        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($productName)) . '.png';

        return [
            'name' => $productName,
            'slug' => Str::slug($productName),
            'image' => $filename,
            'price' => $this->faker->numerify('####'),
            'is_active' => true,
            'upload_successful' => false,
            'disk' => 'public',
            'description' => $this->faker->sentence()
        ];
    }
}
