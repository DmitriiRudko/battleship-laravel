<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasAlreadyBegun {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        if ($user->game->status === Game::GAME_HAS_NOT_BEGUN_STATUS) {
            return response()->error(403, 'Game has not been started');
        }

        return $next($request);
    }
}
