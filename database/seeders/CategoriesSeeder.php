<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Розы', 'slug' => 'roses'],
            ['name' => 'Тюльпаны', 'slug' => 'tulips'],
            ['name' => 'Лилии', 'slug' => 'lilies'],
            ['name' => 'Орхидеи', 'slug' => 'orchids'],
            ['name' => 'Букеты', 'slug' => 'bouquets'],
            ['name' => 'Композиции', 'slug' => 'compositions'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
