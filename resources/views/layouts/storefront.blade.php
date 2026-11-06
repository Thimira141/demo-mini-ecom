<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <tallstackui:script />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body x-bind:class="{ 'dark bg-gray-950': darkTheme, 'bg-white': !darkTheme }">
    <x-toast />
    <x-dialog />
    <x-layout>
        <x-slot:header>
            <x-layout.header>
                <x-slot:left>
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('logo-full.png') }}" alt="Demo Mini E-commerce" class="h-10 w-auto" />
                    </a>
                </x-slot:left>

                <x-slot:middle>
                    <div class="flex items-center gap-2">
                        <livewire:store-front.products.products-search />
                        <x-button.circle icon="funnel" x-data x-tooltip="Filters"
                            x-on:click="$tsui.open.slide('products-filter')" />
                    </div>
                </x-slot:middle>

                <x-slot:right>
                    <div class="mx-1">
                        <livewire:store-front.cart.cart-list />
                    </div>
                    <div class="mx-1">
                        <x-dropdown>
                            <x-slot:action>
                                <x-button.circle x-on:click="show = !show" icon="cog" x-data x-tooltip="Menu" />
                            </x-slot:action>
                            <x-slot:header>
                                <x-theme-switch block />
                            </x-slot:header>
                            <x-dropdown.items icon="cog" text="Login" />
                        </x-dropdown>
                    </div>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>

        {{-- filter slide --}}
        <x-slide id="products-filter" title="Filter">
            <x-accordion chevron="left">
                <x-accordion.items title="Category Filter" icon="tag" id="category-filter">
                    <livewire:store-front.products.products-category-filter />
                </x-accordion.items>
                <x-accordion.items title="Price Range" icon="currency-dollar" id="price-range">
                    <livewire:store-front.products.products-price-filter />
                </x-accordion.items>
            </x-accordion>
        </x-slide>
        {{-- slot --}}
        {{ $slot }}
        {{-- end slot --}}
        <x-back-to-top />
    </x-layout>

    @livewireScripts
</body>

</html>
