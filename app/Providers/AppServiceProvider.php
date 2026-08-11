<?php

namespace App\Providers;

use App\Services\MenuService;
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
        //
        View::composer('frontend.*', function ($view) {
            $view->with('menus', app(MenuService::class)->frontend());
        });
        View::composer('admin.*', function ($view) {
            $view->with('menus', app(MenuService::class)->admin());
        });
    }
}
