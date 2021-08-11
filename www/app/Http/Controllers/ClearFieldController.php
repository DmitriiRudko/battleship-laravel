<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClearFieldController extends Controller {
    public function cllearField(): JsonResponse {
        $user = Auth::user();

        foreach ($user->ships as $ship) {
            $ship->delete();
        }

        return response()->json(['success' => true]);
    }
}
