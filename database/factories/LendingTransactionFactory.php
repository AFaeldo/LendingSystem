<?php

namespace Database\Factories;

use App\Models\LendingTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Borrower;
use App\Models\InventoryItem;

/**
 * @extends Factory<LendingTransaction>
 */
class LendingTransactionFactory extends Factory
{
    protected $model = LendingTransaction::class;

    public function definition(): array
    {
        $item = InventoryItem::factory()->create();
        $borrower = Borrower::factory()->create();
        $qty = fake()->numberBetween(1, min(3, $item->available ?: 1));
        $borrowedAt = fake()->dateTimeBetween('-30 days', 'now');
        $dueAt = (clone $borrowedAt)->modify('+7 days');
        return [
            'borrower_id' => $borrower->id,
            'inventory_item_id' => $item->id,
            'quantity' => $qty,
            'borrowed_at' => $borrowedAt,
            'due_at' => $dueAt,
            'status' => 'active',
            'processed_by' => null,
        ];
    }
}
