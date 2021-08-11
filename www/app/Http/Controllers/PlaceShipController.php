<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ShipModel;
use Illuminate\Support\Facades\Auth;

class PlaceShipController extends Controller {
    public function placeShip(int $id, string $ship, string $orientation, int $x, int $y): JsonResponse {
        /*ВАЛИДАЦИЯ*/
        $user = Auth::user();
        $user->messagesScope(1628656851);
        $size   = (int)explode('-', $ship)[0];
        $number = (int)explode('-', $ship)[1];
        /*ПРОВЕРКА*/
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

    public function action(int $id, ApiRequest $request): JsonResponse {
        $request->validate([
            'x'           => 'digits_between:0,9|required',
            'y'           => 'digits_between:0,9|required',
            'orientation' => 'in:vertical,horizontal|required',
            'ship'        => 'regex:[1-4]-[1-4]|required',
        ]);

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

    public function placeManyShips(int $id, array $ships): JsonResponse {
        /*ВАЛИДАЦИЯ*/
        foreach ($ships as $shipElement) {
            extract($shipElement);
            $this->placeShip($id, $ship, $orientation, $x, $y);
        }

        return response()->json(['success' => true]);
    }

    public function removeShip(int $id, string $ship): JsonResponse {
        /*ВАЛИДАЦИЯ*/
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
