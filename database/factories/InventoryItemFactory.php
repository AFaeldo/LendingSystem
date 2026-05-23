<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1,50);
        return [
            'item_code' => strtoupper(fake()->bothify('ITM-####')),
            'name' => fake()->words(3, true),
            'category_id' => null,
            'description' => fake()->sentence(),
            'quantity' => $qty,
            'available' => $qty,
            'condition' => 'good',
            'status' => 'available',
            'image_path' => null,
        ];
    }
}
