<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();

        // Custom Blade directive: ONLY genuine B2B Wholesale Customers (user_type === 'CUSTOMER')
        Blade::if('customer', function () {
            $cust = Auth::guard('customer')->user();
            if ($cust && $cust->user_type === 'CUSTOMER') {
                return true;
            }
            $web = Auth::guard('web')->user();
            if ($web && $web->user_type === 'CUSTOMER') {
                return true;
            }
            return false;
        });

        View::composer('frontend.partials.header', function ($view) {
            $navCategories = Category::whereNull('parent_id')
                ->where('status', 'ACTIVE')
                ->get();

            $view->with('navCategories', $navCategories);
        });
    }
}