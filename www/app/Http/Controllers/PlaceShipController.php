<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Models\ShipModel;
use Illuminate\Support\Facades\Auth;

class PlaceShipController extends Controller {
    public function placeShip(int $id, string $code, string $ship, string $orientation, int $x, int $y): void {
        /*ВАЛИДАЦИЯ*/
        Auth::user();
        $userModel = new UserModel();
        $user = $userModel->getUserInfoByCode($code);
        $size = explode('-', $ship)[0];
        $number = explode('-', $ship)[1];
        $shipModel = new shipModel();
        $shipModel->placeShip($id, $user['id'], $size, $number, $orientation, $x, $y);

    }

    public function action(int $id, string $code, Request $request) {
        $controller = new PlaceShipController();
        extract($request->post());
        /**
         * @var array $ships
         * @var string $ship
         * @var string $orientation
         * @var int $x
         * @var int $y
         */
        if ($request->post('ships')) {
            $this->placeManyShips($id, $code, $ships);
        } else if ($request->post('x') && $request->post('y')) {
            $this->placeShip($id, $code, $ship, $orientation, $x, $y);
        } else {
            $this->placeShip($id, $code, $ship, $orientation, $x, $y);
        }
    }

    public function placeManyShips(int $id, string $code, array $ships): void {
        /*ВАЛИДАЦИЯ*/

        $userModel = new UserModel();
        $user = $userModel->getUserInfoByCode($code);
    }

    public function removeShip(int $id, string $code, string $ship): void {
        /*ВАЛИДАЦИЯ*/

        $userModel = new UserModel();
        $user = $userModel->getUserInfoByCode($code);
        $size = explode('-', $ship)[0];
        $number = explode('-', $ship)[1];
        $shipModel = new shipModel();
    }
}
