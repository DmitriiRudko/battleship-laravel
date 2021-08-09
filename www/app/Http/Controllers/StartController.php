<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameModel;

class StartController extends Controller {
    public function newGame() {
        $game = new GameModel();
        $game->newGame();
    }
}
