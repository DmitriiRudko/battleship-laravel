<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceShipRequest;
use App\Services\PlaceShip\PlaceShipService;
use Illuminate\Http\JsonResponse;
use App\Models\ShipModel;
use Illuminate\Support\Facades\Auth;

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
                return $this->placeShip($id, $ship, $orientation, $x, $y);
            } else {
                return $this->turn($sameShip, $orientation, $x, $y);
            }

        } else {
            return $this->removeShip($id, $ship);
        }
    }

    public function placeShip(int $id, string $ship, string $orientation, int $x, int $y): JsonResponse {
        $user   = Auth::user();
        $size   = (int)explode('-', $ship)[0];
        $number = (int)explode('-', $ship)[1];

        if (!$this->placeShipService->isShipValid($size, $number, $x, $y, $orientation, $user->id, $user->game->id, $user->ships)) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Ship is unable to place here',
            ]);
        }

        ShipModel::newShip($id, $user->id, $x, $y, $size, $number, $orientation);

        return response()->json(['success' => true]);
    }

    public function placeManyShips(int $id, array $ships): JsonResponse {
        foreach ($ships as $shipElement) {
            $ship        = $shipElement['ship'];
            $x           = $shipElement['x'];
            $y           = $shipElement['y'];
            $orientation = $shipElement['orientation'];
            $this->placeShip($id, $ship, $orientation, $x, $y);
        }

        return response()->json(['success' => true]);
    }

    public function removeShip(int $id, string $ship): JsonResponse {
        $user   = Auth::user();
        $size   = (int)explode('-', $ship)[0];
        $number = (int)explode('-', $ship)[1];

        $exists = $user->ships->where('game_id', $id)
            ->where('user_id', $user->id)
            ->where('size', $size)
            ->firstWhere('number', $number);

        $exists->delete();

        return response()->json(['success' => true]);
    }

    public function turn(ShipModel $ship, string $newOrientation, $x, $y): JsonResponse {
        $user = Auth::user();

        $shipsExcludeThis = $user->ships->where('id', '!=', $ship->id);

        if (!$this->placeShipService->isShipValid($ship->size, $ship->number, $x, $y, $newOrientation, $user->id, $user->game->id, $shipsExcludeThis)) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Ship is unable to place here',
            ]);
        }

        $ship->orientation = $newOrientation;
        $ship->x = $x;
        $ship->y = $y;

        $ship->saveOrFail();

        return response()->json(['success' => true]);
    }
}
