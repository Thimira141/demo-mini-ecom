<div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-6 mb-3">
        @foreach ($products as $product)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col">
                {{-- image --}}
                <img src="{{ asset('logo-full.png') }}" alt="text" class="w-auto h-48 object-cover rounded-md mb-3">
                {{-- product name --}}
                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ $product->name }}</h3>

                <p class="text-gray-600 text-sm flex grow">
                    {{ $product->description }}
                </p>
                {{-- bottom row --}}
                <div class="mt-3 flex items-center justify-between">
                    {{-- price tag --}}
                    <span class="text-blue-600 font-bold">
                        $ {{ $product->price }}
                    </span>
                    {{-- cart option --}}
                    @if ($product->stocks)
                    <x-button icon="shopping-cart" tooltip="Add to cart" />
                    @else
                    <x-badge text="Out of stocks" color="red" />
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{-- pagination --}}
    <div class="mb-3">
        {{-- use x-table without headers, only display paginate --}}
        <x-table :rows="$products" paginate />
        {{-- {{ $products->links() }} --}}
    </div>
</div>
