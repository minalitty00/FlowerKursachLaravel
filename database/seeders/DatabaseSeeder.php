<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CategoriesSeeder::class,
        ]);

        // Create test products for each category
        $this->createTestProducts();
    }

    /**
     * Create test products for demonstration
     */
    private function createTestProducts(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            return;
        }

        $products = [
            // Розы
            [
                'name' => 'Красные розы "Классика"',
                'description' => 'Букет из 11 красных роз высшего качества. Идеальный подарок для выражения любви и страсти.',
                'price' => 2500.00,
                'stock_quantity' => 25,
                'category_slug' => 'roses',
            ],
            [
                'name' => 'Белые розы "Нежность"',
                'description' => 'Элегантный букет из 15 белых роз. Символ чистоты и невинности.',
                'price' => 3200.00,
                'stock_quantity' => 18,
                'category_slug' => 'roses',
            ],
            [
                'name' => 'Розовые розы "Романтика"',
                'description' => 'Букет из 21 розовой розы. Выражает восхищение и благодарность.',
                'price' => 4500.00,
                'stock_quantity' => 12,
                'category_slug' => 'roses',
            ],
            // Тюльпаны
            [
                'name' => 'Тюльпаны "Весенний микс"',
                'description' => 'Яркий букет из 25 разноцветных тюльпанов. Приносит весеннее настроение.',
                'price' => 1800.00,
                'stock_quantity' => 30,
                'category_slug' => 'tulips',
            ],
            [
                'name' => 'Красные тюльпаны',
                'description' => 'Букет из 15 красных тюльпанов. Символ настоящей любви.',
                'price' => 1500.00,
                'stock_quantity' => 22,
                'category_slug' => 'tulips',
            ],
            // Лилии
            [
                'name' => 'Белые лилии "Королевские"',
                'description' => 'Роскошный букет из 7 белых лилий. Символ величия и благородства.',
                'price' => 3500.00,
                'stock_quantity' => 15,
                'category_slug' => 'lilies',
            ],
            [
                'name' => 'Розовые лилии',
                'description' => 'Нежный букет из 5 розовых лилий. Выражает восхищение и уважение.',
                'price' => 2800.00,
                'stock_quantity' => 20,
                'category_slug' => 'lilies',
            ],
            // Орхидеи
            [
                'name' => 'Орхидея Фаленопсис',
                'description' => 'Элегантная орхидея в горшке. Долговечный и изысканный подарок.',
                'price' => 4200.00,
                'stock_quantity' => 10,
                'category_slug' => 'orchids',
            ],
            [
                'name' => 'Орхидея "Белая жемчужина"',
                'description' => 'Белая орхидея премиум класса. Символ роскоши и утонченности.',
                'price' => 5500.00,
                'stock_quantity' => 8,
                'category_slug' => 'orchids',
            ],
            // Букеты
            [
                'name' => 'Букет "Летний сад"',
                'description' => 'Микс из роз, хризантем и альстромерий. Яркий и жизнерадостный букет.',
                'price' => 3800.00,
                'stock_quantity' => 14,
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Букет "Нежность"',
                'description' => 'Пастельный букет из роз, эустомы и гипсофилы. Идеален для романтического подарка.',
                'price' => 4500.00,
                'stock_quantity' => 11,
                'category_slug' => 'bouquets',
            ],
            [
                'name' => 'Букет "Яркие эмоции"',
                'description' => 'Контрастный букет из красных роз и белых лилий. Выражает сильные чувства.',
                'price' => 5200.00,
                'stock_quantity' => 9,
                'category_slug' => 'bouquets',
            ],
            // Композиции
            [
                'name' => 'Композиция "Весенняя свежесть"',
                'description' => 'Цветочная композиция в корзине с тюльпанами и нарциссами.',
                'price' => 4800.00,
                'stock_quantity' => 7,
                'category_slug' => 'compositions',
            ],
            [
                'name' => 'Композиция "Роскошь"',
                'description' => 'Премиум композиция с розами, орхидеями и декоративной зеленью в элегантной вазе.',
                'price' => 8500.00,
                'stock_quantity' => 5,
                'category_slug' => 'compositions',
            ],
        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category_slug'];
            unset($productData['category_slug']);

            $category = $categories->firstWhere('slug', $categorySlug);
            
            if ($category) {
                Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock_quantity' => $productData['stock_quantity'],
                    'category_id' => $category->id,
                    'image_path' => null, // Images can be added later via admin panel
                ]);
            }
        }
    }
}
