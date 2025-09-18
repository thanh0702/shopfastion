<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
                $cart = Cart::where('user_id', auth()->id())->withCount('items')->first();
                $cartCount = $cart ? $cart->items_count : 0;
                $view->with('wishlistCount', $wishlistCount);
                $view->with('cartCount', $cartCount);
            } else {
                $view->with('wishlistCount', 0);
                $view->with('cartCount', 0);
            }
        });
    }
}
