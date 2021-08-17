<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserReadyController extends Controller {
    public function getUserReady(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $user->getReady();

        if ($user->enemy->ready) {
            $user->game->startGame();
        }

        return response()->success(UserResource::make($user)->resolve());
    }
}
