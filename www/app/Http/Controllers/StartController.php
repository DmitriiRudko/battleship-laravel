<?php

namespace App\Http\Controllers;

use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\GameModel;
use App\Models\UserModel;

class StartController extends Controller {
    public function newGame(): JsonResponse {
        $userModel = new UserModel();
        $initiator = $userModel->newUser();
        $invited = $userModel->newUser();

        $game = new GameModel();
        $gameId = $game->newGame($initiator['id'], $invited['id']);

        $gameInfo = [
            'id' => $gameId,
            'code' => $initiator['code'],
            'invited' => $invited['code'],
            'success' => true,
        ];

        return response()->json($gameInfo);
    }
}
