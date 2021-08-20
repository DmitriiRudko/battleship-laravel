<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeforeDenieIfNotAuthenticated {
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
        return response()->error(403, 'User unauthorized');
    }
}
