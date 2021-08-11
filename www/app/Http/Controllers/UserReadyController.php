<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserReadyController extends Controller {
    public function getUserReady(): JsonResponse {
        $user = Auth::user();

        $user->ready = UserModel::READY_STATUS;
        if ($user->enemy->ready === UserModel::READY_STATUS) {
            $user->game->status = UserModel::READY_STATUS;
        }

        return response()->json([
            'enemyReady' => (bool)$user->enemy->ready,
            'success'    => true,
        ]);
    }
}
