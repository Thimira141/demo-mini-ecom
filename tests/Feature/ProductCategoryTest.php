<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test the relation between pivot table
    */
    #[Test]
    public function product_categories_pivot(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create();

        $product->categories()->attach([$category->id]);

        $this->assertDatabaseHas("product", ['name' => $product->name]);
        $this->assertDatabaseHas('category', ['name'=> $category->name]);

        $this->assertDatabaseHas('product_category', ['product_id' => $product->id]);
        $this->assertDatabaseHas('product_category', ['category_id' => $category->id]);
    }
}
