<?php

namespace Database\Factories;

use App\Models\Borrower;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Borrower>
 */
class BorrowerFactory extends Factory
{
    protected $model = Borrower::class;

    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'middlename' => fake()->optional()->firstName(),
            'gender' => fake()->randomElement(['Male','Female','Other']),
            'purok' => 'Purok ' . fake()->numberBetween(1,10),
            'address' => fake()->address(),
            'contact' => fake()->phoneNumber(),
            'organization' => fake()->optional()->company(),
            'status' => 'active',
        ];
    }
}
