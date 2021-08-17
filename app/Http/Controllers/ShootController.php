<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShootRequest;
use App\Models\Game;
use App\Models\Shot;
use App\Services\Shoot\ShootService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShootController extends Controller {
    private ShootService $shootService;

    public function __construct(ShootService $shootService) {
        $this->shootService = $shootService;
    }

    public function shoot(int $id, ShootRequest $request): JsonResponse {
        $user = Auth::user();
        if ($user->game->turn !== $user->id || $user->game->status === Game::GAME_HAS_NOT_BEGUN_STATUS) {
            return response()->error(400, 'Not your turn');
        }

        $x = $request->post('x');
        $y = $request->post('y');

        if ($this->shootService->isVisibleCell($x, $y, $user->shots)) {
            return response()->error(400, 'Unable to shoot here');
        }

        $shootResult = $this->shootService->shoot($x, $y, $user->enemy->ships);

        $shot = Shot::newShot($x, $y, $id, $user->id);

        $shots = $user->shots()->get();

        if (isset($shootResult)) {
            if (!$this->shootService->shipHealth($shootResult, $shots)) {
                $this->shootService->shootAroundShip($shootResult);
            }

            if ($this->shootService->isOver($user->enemy->ships, $shots)) {
                $user->game->gameOver();
            }

        } else {
            $user->game->switchTurn();
        }

        return response()->success();
    }
}
