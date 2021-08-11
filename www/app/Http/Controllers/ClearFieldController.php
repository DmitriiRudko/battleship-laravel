<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClearFieldController extends Controller {
    public function cllearField(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        foreach ($user->ships as $ship) {
            $ship->delete();
        }

        return response()->json(['success' => true]);
    }
}
