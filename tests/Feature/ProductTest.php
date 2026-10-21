<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check database work on product table.
     */
    #[Test]
    public function has_product(): void
    {

        $product = [
            'name' => 'Smart Fan 124-CC Dx',
            'description' => 'random fan with AI junk overpriced',
            'price' => 1599.99,
            'stocks' => 10
        ];

        Product::create($product);

        $this->assertDatabaseHas('product', ['name' => $product['name']]);
    }
}
