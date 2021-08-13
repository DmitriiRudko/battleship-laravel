<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DenieIfNotAuthenticated {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        if (Auth::user()) {
            if (Auth::user()->game->id === (int)$request->id) {
                return $next($request);
            }
        }
        return [
            'success' => false,
            'error'   => 403,
            'message' => 'User unauthorized',
        ];
    }
}
