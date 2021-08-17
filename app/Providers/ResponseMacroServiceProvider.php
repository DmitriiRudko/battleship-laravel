<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        Response::macro('success', function ($res = []) {
            return Response::json(array_merge(['success' => true], $res));
        });

        Response::macro('error', function ($code, $message) {
            return Response::json([
                'success' => false,
                'error'   => $code,
                'message' => $message,
            ]);
        });
    }
}
