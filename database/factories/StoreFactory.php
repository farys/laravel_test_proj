<?php

namespace Database\Factories;

use App\Models\Store;
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
        $domainName = fake()->unique()->domainName();

        return [
            'domain' => $domainName,
            'company_name' => fake()->company(),
            'site_title' => fake()->title(),
            'site_address' => 'https://'.$domainName,
            'account_number' => fake()->numerify('## #### #### #### #### #### ####'),
            'account_receiver' => fake()->company(),
            'account_bank_name' => fake()->randomElement(['Example Bank', 'Second Example Bank']),
        ];
    }
}
