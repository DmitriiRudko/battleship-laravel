<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use Illuminate\Http\JsonResponse;
use App\Models\Game;
use App\Models\User;

class StartController extends Controller {
    public function newGame(): GameResource {
        $initiator = User::newUser();
        $invited   = User::newUser();
        $game      = Game::newGame($initiator, $invited);

        return response()->success(GameResource::make($game));
    }
}
