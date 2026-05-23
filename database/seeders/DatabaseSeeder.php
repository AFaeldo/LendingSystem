<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an initial admin user
        User::factory()->create([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@local.test',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Demo data
        \App\Models\Borrower::factory()->count(20)->create();
        \App\Models\InventoryItem::factory()->count(30)->create();

        // Create sample lendings
        \App\Models\LendingTransaction::factory()->count(15)->create();
    }
}
