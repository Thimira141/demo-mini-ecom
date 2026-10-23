<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\StoreFront\Products as Products;


Route::get('/', Products\ProductsList::class)->name('home');
