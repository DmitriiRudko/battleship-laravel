<?php

namespace App\Http\Controllers;

use http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\GameModel;
use App\Models\UserModel;

class StartController extends Controller {
    public function newGame(): JsonResponse {
        $initiator = new UserModel();
        $invited   = new UserModel();
        $game      = new GameModel();

        $initiator->code = uniqid();
        $initiator->saveOrFail();

        $invited->code = uniqid();
        $invited->saveOrFail();

        $game->initiator_id = $initiator->id;
        $game->invited_id   = $invited->id;
        $game->turn         = [$initiator->id, $invited->id][random_int(0, 1)];
        $game->saveOrFail();

        $gameInfo = [
            'id'      => $game->id,
            'code'    => $initiator->code,
            'invited' => $invited->code,
            'success' => true,
        ];

        return response()->json($gameInfo);
    }
}
