<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Furniture'],
            ['name' => 'Electronics / IT Equipment'],
            ['name' => 'Office / Stationery Supplies'],
            ['name' => 'Medical / Emergency Gear'],
            ['name' => 'Events / Tents & Stages'],
            ['name' => 'Tools / Maintenance'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']]);
        }
    }
}
