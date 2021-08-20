<?php

namespace App\Http\Middleware;

use App\Models\Game;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhoseTurn {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        if ($user->game->turn !== $user->id) {
            return response()->error(403, 'Not your turn');
        }

        return $next($request);
    }
}
