<?php

namespace App\Providers;

use App\Services\PlaceShip\PlaceShipService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // $this->app->singleton(PlaceShipService::class, fn($app) => new PlaceShipService());
    }
}
