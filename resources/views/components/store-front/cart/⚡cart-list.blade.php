<?php

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\On;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public $cart;
    public $cartOriginal;
    public $cartItems;
    public $headers = [['index' => 'id', 'label' => '#'], ['index' => 'name', 'label' => 'product'], ['index' => 'quantity', 'label' => 'Quantity'], ['index' => 'price', 'label' => 'Price'], ['index' => 'action', 'label' => 'Actions']];

    public function mount()
    {
        $this->loadCart();
    }

    /**
     * update values
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    #[On('cart-updated')]
    public function updateCartList()
    {
        $this->loadCart();
    }

    /**
     * update the cart data
     * @param int $productId
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function updateCart(int $productId)
    {
        // todo: move to db cart options
        try {
            // update or delete
            $this->cart[$productId] ?? false ? session()->put("cart.$productId", $this->cart[$productId]) : session()->forget("cart.$productId");
            // diff = (new_quantity - old_quantity) ; +/- value
            $diff = ($this->cart[$productId] ?? 0) - $this->cartOriginal[$productId];
            // update stocks
            $product = $this->cartItems->findOrFail($productId);
            // inverse the diff before update
            $product->update(['stocks' => $product->stocks + $diff * -1]);
            // refresh cart data
        } catch (\Exception $e) {
            // log the error
            Log::error('ERROR:: Updating product stocks failed in updateCart(int $productId), after putting items to session! ', ['error' => $e->getMessage()]);
            // rollback session changes
            session()->put("cart.$productId", $this->cartOriginal[$productId]);
            // inform user
            $this->dialog()->error('DB ERROR', 'Updating product stocks failed!')->send();
        }
        $this->loadCart();
    }

    /**
     * delete product from cart, wrapper
     * @param int $productId
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function deleteItemFromCart(int $productId)
    {
        unset($this->cart[$productId]);
        $this->updateCart($productId);
    }

    /**
     * load data
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    private function loadCart()
    {
        $this->cart = $this->cartOriginal = session()->get('cart', []);
        $this->cartItems = Product::findMany(array_keys($this->cart));
    }
};
?>

<div>
    <div class="relative ">
        <x-button.circle icon="shopping-cart" x-data x-tooltip="Cart" x-on:click="$tsui.open.modal('cart-modal')" />
        @if (count($cart))
            <div class="absolute top-0 right-0 size-2.5 bg-red-600 rounded-lg"></div>
        @endif
    </div>
    {{-- cart modal --}}
    <x-modal title="Cart" id="cart-modal" size="4xl" scrollable>
        <x-table :rows="$cartItems" :headers="$headers">
            @interact('column_quantity', $row)
                <x-number min="1" wire:model.live="cart.{{ $row['id'] }}"
                    max="{{ $row['stocks'] + $this->cart[$row['id']] }}" wire:change="updateCart({{ $row['id'] }})"
                    centralized />
            @endinteract
            @interact('column_price', $row)
                <x-badge text="$ {{ $this->cart[$row['id']] * $row['price'] }}" md />
            @endinteract
            @interact('column_action', $row)
                <x-button.circle icon="trash" color="red" x-data x-tooltip="Remove from cart"
                    wire:click="deleteItemFromCart({{ $row['id'] }})" />
            @endinteract
        </x-table>
        <div class="mt-2">
            <x-badge md>
                Total Price: $
                {{ collect($this->cart??[])->map(fn(int $quantity, int $productId) => $this->cartItems->findOrFail($productId)->price * $quantity)->sum() }}
            </x-badge>
        </div>
    </x-modal>
</div>
