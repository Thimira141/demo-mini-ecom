<?php

use App\Models\Cart;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * cleanup expired carts,
 */
Schedule::call(function() {
    // collect carts
    $carts = Cart::where('expires_at', '>', now())->get();
    // move products to their shelves

})->everyMinute();
