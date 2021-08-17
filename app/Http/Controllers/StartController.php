<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameResource;
use Illuminate\Http\JsonResponse;
use App\Models\Game;
use App\Models\User;

/**
 * @OA\Post(
 *     path= "/start",
 *     operationId = "Create a new game",
 *     tags= {"API"},
 *     summary = "Creates a new game",
 *     @OA\Response(
 *          response="200",
 *          description= "New game created successfully",
 *          @OA\JsonContent(
 *              required={"id", "code", "invited", "success"},
 *              @OA\Property(property="id", type="int"),
 *              @OA\Property(property="code", type="string", example="61122735b150c"),
 *              @OA\Property(property="invited", type="strin", example="6112273ab92c1"),
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */
class StartController extends Controller {
    public function newGame(): JsonResponse {
        $initiator = User::newUser();
        $invited   = User::newUser();
        $game      = Game::newGame($initiator, $invited);

        return response()->success(GameResource::make($game)->resolve());
    }
}
