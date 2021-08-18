<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceShipRequest;
use App\Services\PlaceShip\PlaceShipService;
use Illuminate\Http\JsonResponse;
use App\Models\Ship;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/place-ship/{id}/{code}",
 *     operationId = "Place ship",
 *     tags= {"API"},
 *     summary = "Places/removes/turns/replaces ship",
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
 *                  @OA\Property(
 *                      property="ship",
 *                      type="strind",
 *                      example= "1-1",
 *                  ),
 *                  @OA\Property(
 *                      property="orientation",
 *                      type="string",
 *                      example="vertical",
 *                  ),
 *                  @OA\Property(
 *                      property="ships",
 *                      type="array",
 *                      @OA\Items(
 *                          @OA\Property(property="x", type="integer", example=0),
 *                          @OA\Property(property="y", type="integer", example=0),
 *                          @OA\Property(property="ship", type="string", example="1-1"),
 *                          @OA\Property(property="orientation", type="string", example="vertical"),
 *                      ),
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
class PlaceShipController extends Controller {

    private PlaceShipService $placeShipService;

    public function __construct(PlaceShipService $placeShipService) {
        $this->placeShipService = $placeShipService;
    }

    public function action(int $id, PlaceShipRequest $request): JsonResponse {
        $user        = Auth::user();
        $ships       = $request->post('ships');
        $ship        = $request->post('ship');
        $x           = $request->post('x');
        $y           = $request->post('y');
        $orientation = $request->post('orientation');

        if ($request->post('ships')) {
            return $this->placeManyShips($id, $ships);

        } elseif (isset($x, $y)) {
            $size     = (int)explode('-', $ship)[0];
            $number   = (int)explode('-', $ship)[1];
            $sameShip = $user->ships->where('size', $size)->firstWhere('number', $number);
            if (is_null($sameShip)) {
                return $this->placeShip($id, $size, $number, $orientation, $x, $y);
            } else {
                return $this->turn($sameShip, $orientation, $x, $y);
            }

        } else {
            return $this->removeShip($id, $ship);
        }
    }

    public function placeShip(int $id, int $size, int $number, string $orientation, int $x, int $y): JsonResponse {
        $user = Auth::user();

        if (!$this->placeShipService->isShipValid($size, $number, $x, $y, $orientation, $user->id, $user->game->id, $user->ships)) {
            return response()->error(400, 'Ship is unable to place here');
        }

        Ship::newShip($id, $user->id, $x, $y, $size, $number, $orientation);

        return response()->success();
    }

    public function placeManyShips(int $id, array $ships): JsonResponse {
        foreach ($ships as $shipElement) {
            $size        = (int)explode('-', $shipElement['ship'])[0];
            $number      = (int)explode('-', $shipElement['ship'])[1];
            $x           = $shipElement['x'];
            $y           = $shipElement['y'];
            $orientation = $shipElement['orientation'];
            $this->placeShip($id, $size, $number, $orientation, $x, $y);
        }

        return response()->success();
    }

    public function removeShip(int $id, string $ship): JsonResponse {
        $user   = Auth::user();
        $size   = (int)explode('-', $ship)[0];
        $number = (int)explode('-', $ship)[1];

        $exists = $user->ships->where('game_id', $id)
            ->where('user_id', $user->id)
            ->where('size', $size)
            ->firstWhere('number', $number);

        if ($exists) {
            $exists->delete();
            return response()->success();
        } else {
            return response()->error(400, 'Ship does not exist');
        }
    }

    public function turn(Ship $ship, string $newOrientation, $x, $y): JsonResponse {
        $user = Auth::user();

        $shipsExcludeThis = $user->ships->where('id', '!=', $ship->id);

        if (!$this->placeShipService->isShipValid($ship->size, $ship->number, $x, $y, $newOrientation, $user->id, $user->game->id, $shipsExcludeThis)) {
            return response()->error(400, 'Ship is unable to place here');
        }

        $ship->orientation = $newOrientation;
        $ship->x           = $x;
        $ship->y           = $y;

        $ship->saveOrFail();

        return response()->success();
    }
}
