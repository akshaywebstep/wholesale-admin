<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        View::composer('frontend.partials.header', function ($view) {
            $navCategories = Category::whereNull('parent_id')
                ->where('status', 'ACTIVE')
                ->get();

            $view->with('navCategories', $navCategories);
        });
    }
}
