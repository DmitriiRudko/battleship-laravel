<?php

namespace App\Http\Controllers;


use App\Http\Requests\ApiRequest;
use App\Http\Resources\StatusResource;
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

        $info               = $this->statusService->getFieldInfo($user->ships, $user->shots, $user->enemy->ships, $user->enemy->shots);
        $info['fieldMy']    = $this->statusService->transpose($info['fieldMy']);
        $info['fieldEnemy'] = $this->statusService->transpose($info['fieldEnemy']);

        return response()->success(StatusResource::make($info)->resolve());
    }
}
