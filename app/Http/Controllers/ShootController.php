<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShootRequest;
use App\Models\GameModel;
use App\Models\ShotModel;
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
        if ($user->game->turn !== $user->id) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Not your turn',
            ]);
        }

        $x = $request->post('x');
        $y = $request->post('y');

        if ($this->shootService->isVisibleCell($x, $y, $user->shots)) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Unable to shoot here',
            ]);
        }

        $shootResult = $this->shootService->shoot($x, $y, $user->enemy->ships);

        $shot          = new ShotModel();
        $shot->x       = $x;
        $shot->y       = $y;
        $shot->game_id = $id;
        $shot->user_id = $user->id;
        $shot->saveOrFail();

        $shots = $user->shots()->get();

        if (isset($shootResult)) {
            if (!$this->shootService->shipHealth($shootResult, $shots)) {
                $this->shootAroundShip($shootResult);
            }
            $total_health = array_reduce(iterator_to_array($user->enemy->ships), function ($carry, $item) use ($shots) {
                $carry += $this->shootService->shipHealth($item, $shots);
                return $carry;
            }, 0);
            if (!$total_health) {
                $user->game->status = GameModel::GAME_OVER_STATUS;
                $user->game->saveOrFail();
            }
        } else {
            $user->game->turn = $user->enemy->id;
            $user->game->saveOrFail();
        }

        return response()->json(['success' => true]);
    }

    public function shootAroundShip($ship) {
        $user = Auth::user();

        switch ($ship->orientation) {
            case 'vertical':
                for ($i = $ship->y - 1; $i <= $ship->y + $ship->size; $i++) {
                    if (!$user->shots->where('x', $ship->x - 1)->firstWhere('y', $i)
                        && ($ship->x - 1) >= 0
                        && $i >= 0
                        && $i <= 9
                    ) {
                        ShotModel::newShot($ship->x - 1, $i, $user->game->id, $user->id);
                    }

                    if (!$user->shots->where('x', $ship->x + 1)->firstWhere('y', $i)
                        && ($ship->x + 1) <= 9
                        && $i >= 0
                        && $i <= 9
                    ) {
                        ShotModel::newShot($ship->x + 1, $i, $user->game->id, $user->id);
                    }
                }
                if (!$user->shots->where('x', $ship->x)->firstWhere('y', $ship->y - 1) && ($ship->y - 1) >= 0) {
                    ShotModel::newShot($ship->x, $ship->y - 1, $user->game->id, $user->id);
                }
                if (!$user->shots->where('x', $ship->x)
                        ->firstWhere('y', $ship->y + $ship->size) && ($ship->y + $ship->size) <= 9) {
                    ShotModel::newShot($ship->x, $ship->y + $ship->size, $user->game->id, $user->id);
                }
                break;
            case 'horizontal':
                for ($j = $ship->x - 1; $j <= $ship->x + $ship->size; $j++) {
                    if (!$user->shots->where('y', $ship->y - 1)->firstWhere('x', $j)
                        && ($ship->y - 1) >= 0
                        && $j >= 0
                        && $j <= 9
                    ) {
                        ShotModel::newShot($j, $ship->y - 1, $user->game->id, $user->id);
                    }

                    if (!$user->shots->where('y', $ship->y + 1)->firstWhere('x', $j)
                        && ($ship->y + 1) <= 9
                        && $j >= 0
                        && $j <= 9
                    ) {
                        ShotModel::newShot($j, $ship->y + 1, $user->game->id, $user->id);
                    }
                }
                if (!$user->shots->where('y', $ship->y)->firstWhere('x', $ship->x - 1) && ($ship->x - 1) >= 0) {
                    ShotModel::newShot($ship->x - 1, $ship->y, $user->game->id, $user->id);
                }
                if (!$user->shots->where('y', $ship->y)
                        ->firstWhere('x', $ship->x + $ship->size) && ($ship->x + $ship->size) <= 9) {
                    ShotModel::newShot($ship->x + $ship->size, $ship->y, $user->game->id, $user->id);
                }
                break;
        }
    }
}
