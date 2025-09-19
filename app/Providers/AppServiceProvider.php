<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CartItem;
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
            try {
                if (auth()->check()) {
                    $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
                    $cart = Cart::where('user_id', auth()->id())->first();
                    $cartCount = $cart ? CartItem::where('cart_id', $cart->id)->count() : 0;
                    $view->with('wishlistCount', $wishlistCount);
                    $view->with('cartCount', $cartCount);
                } else {
                    $view->with('wishlistCount', 0);
                    $view->with('cartCount', 0);
                }
            } catch (\Exception $e) {
                // Handle DB connection issues during build/package discovery
                $view->with('wishlistCount', 0);
                $view->with('cartCount', 0);
            }
        });
    }
}
