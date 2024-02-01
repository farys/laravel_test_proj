<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'domain' => fake()->unique()->domainName(),
            //'watermark_filename' => fake()->file(),
            'company_name' => fake()->company(),
            'site_title' => fake()->title(),
        ];
    }
}
