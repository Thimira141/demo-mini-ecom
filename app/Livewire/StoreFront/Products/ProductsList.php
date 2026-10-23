<?php

namespace App\Livewire\StoreFront\Products;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Layout('layouts::storefront')]
class ProductsList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public ?string $search = '';
    public ?array $categories = [];
    public ?array $priceRange = [];


    /**
     * listen to event -> update-search
     * @param mixed $term
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    #[On('update-search')]
    public function updateSearch(?string $term): void
    {
        $this->search = $term;
    }

    /**
     * listen to event -> update-category-filter
     * @param mixed $selected
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    #[On('update-category-filter')]
    public function updateCategories(?array $selected=[]): void
    {
        $this->categories = $selected;
    }

    /**
     * listen to event -> update-price-filter
     * @param mixed $price
     * @return void
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    #[On('update-price-filter')]
    public function updatePriceRange(?array $price)
    {
        $this->priceRange = $price;
    }

    /**
     * render
     * @return \Illuminate\Contracts\View\View
     * @author Thimira Dilshan <thimirad865@gmail.com>
     */
    public function render()
    {
        $products = Product::query()
            ->with('categories')
            ->when($this->search, function ($query) {
                $query->whereLike('name', (string) '%' . $this->search . '%');
                // $query->where('description', 'LIKE', (string) '%' . $this->search . '%');
            })
            ->when($this->priceRange, function ($query) {
                $query->whereBetween('price', $this->priceRange);
            })
            ->when($this->categories, function ($query) {
                $query->whereHas('categories', function ($categoryQuery) {
                    $categoryQuery->whereIn('category.id', $this->categories);
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('livewire.store-front.products.products-list', compact('products'));
    }
}
