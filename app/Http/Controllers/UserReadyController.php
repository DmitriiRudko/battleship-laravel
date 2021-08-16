<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\GameModel;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserReadyController extends Controller {
    public function getUserReady(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $user->ready = UserModel::READY_STATUS;
        $user->saveOrFail();

        if ($user->enemy->ready){
            $user->game->status = GameModel::GAME_HAS_BEGUN_STATUS;
            $user->game->saveOrFail();
        }

        return response()->json([
            'enemyReady' => (bool)$user->enemy->ready,
            'success'    => true,
        ]);
    }
}
