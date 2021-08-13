<?php

namespace App\Http\Controllers;


use App\Http\Requests\ApiRequest;
use App\Services\Status\StatusService;
use Illuminate\Support\Facades\Auth;

class StatusController {
    public function getGameStatus(ApiRequest $request) {
        $user = Auth::user();
        $info = [
            'id'         => $user->game->id,
            'invite'     => $user->game->invited->code,
            'status'    =>  $user->game->status,
            'myTurn'     => $user->id === $user->game->turn,
            'meReady'    => (bool)$user->ready,
            'usedPlaces' => array_map(fn($ship) => $ship->size . '-' . $ship->number, iterator_to_array($user->ships)),
        ];

        if ($request->post('short')) return response()->json($info);

        $info = array_merge($info, StatusService::getFieldInfo(
            $user->ships,
            $user->shots,
            $user->enemy->ships,
            $user->enemy->shots
        ));
        return response()->json($info);
    }
}
