<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShootRequest;
use App\Models\Game;
use App\Models\Shot;
use App\Services\Shoot\ShootService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/shot/{id}/{code}",
 *     operationId = "Shots",
 *     tags= {"API"},
 *     summary = "Shoots",
 *     @OA\Parameter(
 *          name= "id",
 *          in= "path",
 *          description= "Game id",
 *          required= true,
 *     ),
 *     @OA\Parameter(
 *          name= "code",
 *          in= "path",
 *          description= "The user's code",
 *          required= true,
 *     ),
 *     @OA\RequestBody(
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required= {"x", "y"},
 *                  @OA\Property(
 *                      property="x",
 *                      type="integer",
 *                      example=0,
 *                  ),
 *                  @OA\Property(
 *                      property="y",
 *                      type="integer",
 *                      example=0,
 *                  ),
 *              ),
 *          ),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description= "OK",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */

class ShootController extends Controller {
    private ShootService $shootService;

    public function __construct(ShootService $shootService) {
        $this->shootService = $shootService;
    }

    public function shoot(int $id, ShootRequest $request): JsonResponse {
        $user = Auth::user();

        $x = $request->post('x');
        $y = $request->post('y');

        if ($this->shootService->isVisibleCell($x, $y, $user->shots)) {
            return response()->error(400, 'Unable to shoot here');
        }

        $shootResult = $this->shootService->shoot($x, $y, $user->enemy->ships);

        Shot::newShot($x, $y, $id, $user->id);

        $shots = $user->shots()->get();

        if (isset($shootResult)) {
            if (!$this->shootService->shipHealth($shootResult, $shots)) {
                $this->shootService->shootAroundShip($shootResult);
            }

            if ($this->shootService->isOver($user->enemy->ships, $shots)) {
                $user->game->gameOver();
            }

        } else {
            $user->game->switchTurn();
        }

        return response()->success();
    }
}
