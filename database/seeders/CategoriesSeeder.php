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
            ['name' => 'Розы', 'slug' => 'rozy'],
            ['name' => 'Тюльпаны', 'slug' => 'tyulpany'],
            ['name' => 'Лилии', 'slug' => 'lilii'],
            ['name' => 'Орхидеи', 'slug' => 'orkhidei'],
            ['name' => 'Пионы', 'slug' => 'piony'],
            ['name' => 'Хризантемы', 'slug' => 'khrizantemy'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
