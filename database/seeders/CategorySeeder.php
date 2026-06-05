<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Furniture / Monobloc'], // Dito papasok ang upuan, mesa, atbp.
            ['name' => 'Electronics / IT Equipment'], // Projector, Sound System, Extension Cord
            ['name' => 'Office / Stationery Supplies'], // Stapler, Puncher
            ['name' => 'Medical / Emergency Gear'], // Wheelchair, Stretcher, Oxygen Tank
            ['name' => 'Events / Tents & Stages'], // Canopy, Folding tent
            ['name' => 'Tools / Maintenance'], // Drills, Ladder, Lawn Mower
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']]);
        }
    }
}
