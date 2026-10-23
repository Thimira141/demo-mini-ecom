<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // create categories
        $categories = Category::factory()->count(10)->create();
        // create products
        $products = Product::factory()->count(25)->create();
        $products->each(function (Product $product) use ($categories) {
            // assign categories to product
            $product->categories()->attach($categories->random(random_int(1,5))->pluck("id")->toArray());
        });
    }
}
