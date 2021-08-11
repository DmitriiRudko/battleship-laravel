<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\ShotModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShootController extends Controller {
    public function shoot(int $id, ApiRequest $request) {
        $user = Auth::user();

        extract($request->post());
        $shot          = new ShotModel();
        $shot->x       = $x;
        $shot->y       = $y;
        $shot->game_id = $id;
        $shot->user_id = $user->id;
        $shot->save();

        return response()->json(['success' => true]);
    }
}
