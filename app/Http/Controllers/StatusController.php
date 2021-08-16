<?php

namespace App\Http\Controllers;


use App\Http\Requests\ApiRequest;
use App\Services\Status\StatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StatusController {
    private StatusService $statusService;

    public function __construct(StatusService $statusService) {
        $this->statusService = $statusService;
    }

    public function getGameStatus(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $info = [
            'game'       => [
                'id'      => $user->game->id,
                'invite'  => $user->game->invited->code,
                'status'  => $user->game->status,
                'myTurn'  => $user->id === $user->game->turn,
                'meReady' => (bool)$user->ready,
            ],
            'success' => true,
            'usedPlaces' => array_map(fn($ship) => $ship->size . '-' . $ship->number, iterator_to_array($user->ships)),
        ];

        if ($request->post('short')) return response()->json($info);

        $info = array_merge($info, $this->statusService->getFieldInfo(
            $user->ships,
            $user->shots,
            $user->enemy->ships,
            $user->enemy->shots
        ));

        $info['fieldMy'] = $this->statusService->transpose($info['fieldMy']);
        $info['fieldEnemy'] = $this->statusService->transpose($info['fieldEnemy']);

        return response()->json($info);
    }
}
