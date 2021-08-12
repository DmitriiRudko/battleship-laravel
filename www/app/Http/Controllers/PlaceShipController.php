<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceShipRequest;
use App\Services\PlaceShip\PlaceShipService;
use Illuminate\Http\JsonResponse;
use App\Models\ShipModel;
use Illuminate\Support\Facades\Auth;

class PlaceShipController extends Controller {
    public function action(int $id, PlaceShipRequest $request): JsonResponse {
        extract($request->post());

        if ($request->post('ships')) {
            parse_str(urldecode($ships), $ships);
            $ships = array_shift($ships);

            return $this->placeManyShips($id, $ships);
        } elseif ($request->post('x') && $request->post('y')) {
            return $this->placeShip($id, $ship, $orientation, $x, $y);
        } else {
            return $this->removeShip($id, $ship);
        }
    }

    public function placeShip(int $id, string $ship, string $orientation, int $x, int $y): JsonResponse {
        $user   = Auth::user();
        $size   = (int)explode('-', $ship)[0];
        $number = (int)explode('-', $ship)[1];

        if (!PlaceShipService::isShipValid($size, $number, $x, $y, $orientation, $user->id, $user->game->id, $user->ships)) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Ship is unable to place here',
            ]);
        }

        $shipModel              = new shipModel();
        $shipModel->game_id     = $id;
        $shipModel->user_id     = $user->id;
        $shipModel->x           = $x;
        $shipModel->y           = $y;
        $shipModel->size        = $size;
        $shipModel->number      = $number;
        $shipModel->orientation = $orientation;
        $shipModel->saveOrFail();

        return response()->json(['success' => true]);
    }

    public function placeManyShips(int $id, array $ships): JsonResponse {
        foreach ($ships as $shipElement) {
            extract($shipElement);
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
}
