<?php

namespace App\Providers;

use App\Services\Auth\CustomTokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [// 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot() {
        $this->registerPolicies();

        // add custom guard
        Auth::extend('custom_token', function ($app, $name, array $config) {
            return new CustomTokenGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request'),
                $storage_key = $config['key']
            );
        });
    }
}
