<?php

use Livewire\Component;
use App\Models\Product;
use TallStackUi\Traits\Interactions;

new class extends Component {
    use Interactions;

    public Product $product;
    public int $quantity = 1;
    public int $maxUnits;

    public function mount()
    {
        session()->forget('cart');# :: D-BUG EMERGENCY
        $cart = session()->get('cart', []);
        $this->quantity = $cart[$this->product->id] ?? 1;
        // note: default quantity start with 1, so we need to tell that 1 is 0
        $this->maxUnits = (($this->quantity===1) ? 0 : $this->quantity) + $this->product->stocks;
    }

    /**
     * add items to cart, update db stocks
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function addToCart()
    {
        // todo: move to db cart options
        // set data to cart
        $cart = session()->get('cart', []);
        $originalQuantity = $cart[$this->product->id]??0;
        $cart[$this->product->id] = $this->quantity;
        session()->put('cart', $cart);
        // update db products.stocks: user put items into his cart, so need to update stocks
        try {
            $this->product->update(['stocks' => $this->quantity]);
            // dispatch event
            $this->dispatch('cart-updated');
            // fix: close modal
            $this->dispatch('close-modal', name: $this->product->id . '-item-add-to-cart');
            // inform user via toast
            $this->toast()
                ->info('Added to cart', "{$this->product->name} : {$this->quantity} added to cart.")
                ->send();
        } catch (\Exception $e) {
            // log the error
            Log::error('DB-ERROR:: Updating product stocks failed, after putting items to session! ', ['error' => $e->getMessage()]);
            // inform user
            $this->dialog()->error('DB ERROR', 'Updating product stocks failed!')->send();
            // rollback session changes,
            $originalQuantity ? session()->put("cart.{$this->product->id}", $originalQuantity) : session()->forget("cart.{$this->product->id}");
        }
    }
};
?>

<div>
    {{-- button --}}
    <x-button icon="shopping-cart" tooltip="Add to cart"x-on:click="$tsui.open.modal('{{ $product->id }}-item-add-to-cart')" />
    {{-- modal --}}
    <x-modal id="{{ $product->id }}-item-add-to-cart" title="Add to cart: {{ $product->name }}">
        <x-number label="Quantity" hint="Press the plus button to increase one by one" wire:model.live="quantity" min="1"
            :max="$maxUnits" centralized />
        {{-- total price display --}}
        <x-badge text="$ {{ $quantity * $product->price }}" md />
        <x-slot:footer>
            <x-button text="Ok" wire:click="addToCart" />
        </x-slot:footer>
    </x-modal>
</div>
