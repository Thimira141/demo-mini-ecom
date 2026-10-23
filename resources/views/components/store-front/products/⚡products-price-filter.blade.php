<?php

use Livewire\Component;
use App\Models\Product;

new class extends Component {
    public int $priceMax = 1;
    public int $priceMin = 0;
    public array $price = [];

    /**
     * mount data
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function mount()
    {
        $this->priceMin = (int) Product::min('price');
        $this->priceMax = (int) Product::max('price');
        $this->price = [$this->priceMin, $this->priceMax];
    }

    /**
     * dispatch event
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function updatedPrice()
    {
        $this->dispatch('update-price-filter', price: $this->price);
    }
};
?>

<div>
    <x-range dual :min="$priceMin" :max="$priceMax" color="emerald" wire:model.live="price" tooltip />
    <div class="w-full flex justify-between mt-1">
        <x-badge text="${{ $priceMin }}" color="primary" light />
        <x-badge text="${{ $price[0] }} -- $ {{ $price[1] }}" color="emerald" light />
        <x-badge text="${{ $priceMax }}" color="primary" light />
    </div>
</div>
