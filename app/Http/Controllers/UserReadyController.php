<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserReadyController extends Controller {
    public function getUserReady(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $user->ready = User::READY_STATUS;
        $user->saveOrFail();

        if ($user->enemy->ready){
            $user->game->status = Game::GAME_HAS_BEGUN_STATUS;
            $user->game->saveOrFail();
        }

        return response()->json([
            'enemyReady' => (bool)$user->enemy->ready,
            'success'    => true,
        ]);
    }
}
