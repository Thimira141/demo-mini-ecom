<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    #[Test]
    public function put_items_to_cart(): void
    {
        $cartData = [
            'session_id' => session()->id(),
            'expires_at' => now()->addMinutes(30)
        ];

        $products = Product::factory(3)->create();

        $cart = Cart::create($cartData);

        $syncData = $products->mapWithKeys(fn($product) => [
            $product->id => ['quantity' => 5]
        ])->toArray();

        $cart->products()->sync($syncData);

        $this->assertDatabaseHas('carts', ['session_id' => session()->id()]);

        $products->each(function ($product) use ($cart) {
            $this->assertDatabaseHas('cart_item', ['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 5]);

        });

    }

    #[Test]
    public function delete_expired_carts()
    {
        // create products
        //Note: total stocks = 13, (cartData_act -> 3 stocks + cartData_expd -> 3 stocks + $cartData_expd2 -> 3 = 9 stocks; 13-9 = 4)
        $products = Product::factory(5)->create(['stocks' => 4]);
        $syncData = $products->mapWithKeys(fn($product) => [
            $product->id => ['quantity' => 3]
        ])->toArray();
        // create a carts
        $cartData_act = Cart::factory()->create(['session_id' => 'active-cart-exp-in-2d', 'expires_at' => now()->addDays(2)]);
        $cartData_expd = Cart::factory()->create(['session_id' => 'expired-cart-exp-was-10min', 'expires_at' => now()->addMinutes(-10)]);
        $cartData_expd2 = Cart::factory()->create(['session_id' => 'expired-cart-exp-was-20min', 'expires_at' => now()->addMinutes(-20)]); // extra load to test
        // sync products with cart
        $cartData_act->products()->sync($syncData);
        $cartData_expd->products()->sync($syncData);
        $cartData_expd2->products()->sync($syncData); // extra load to test

        // check data in db
        $this->assertDatabaseHas('carts', ['session_id' => 'active-cart-exp-in-2d']);
        $this->assertDatabaseHas('carts', ['session_id' => 'expired-cart-exp-was-10min']);

        $products->each(function ($product) use ($cartData_act, $cartData_expd) {
            $this->assertDatabaseHas('cart_item', ['cart_id' => $cartData_act->id, 'product_id' => $product->id, 'quantity' => 3]);
            $this->assertDatabaseHas('cart_item', ['cart_id' => $cartData_expd->id, 'product_id' => $product->id, 'quantity' => 3]);
        });

        // look for expired carts
        Cart::wherePast('expires_at')->with('cartItems')->each(function(Cart $cart) {
            // move carts products/quantity to products.stocks
            $cart->cartItems->each(function($item) {
                try {
                    Product::findOrFail($item->product_id)->increment('stocks', $item->quantity);
                } catch (\Exception $e) {
                    Log::error('E:: update stocks when carts clearing failed!', [
                        'cart_item' => $item->toArray(),
                        'error' => $e->getMessage()
                    ]);
                }
            });
            // delete cart
            $cart->delete();
        });

        $products->each(function ($product) use ($cartData_expd) {
            $this->assertDatabaseMissing('cart_item', ['cart_id' => $cartData_expd->id, 'product_id' => $product->id, 'quantity' => 3]);
            $this->assertDatabaseHas('product', ['id' => $product->id, 'stocks' => 10]);
        });


    }
}
