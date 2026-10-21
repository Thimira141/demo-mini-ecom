<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Check database work on category table.
     */
    #[Test]
    public function has_category(): void
    {
        $category = [
            'name' => 'Electric',
            'description' => 'electric appliance category',
        ];

        Category::create($category);

        $this->assertDatabaseHas('category', ['name'=> $category['name']]);
    }
}
