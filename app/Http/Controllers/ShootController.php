<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShootRequest;
use App\Models\GameModel;
use App\Models\ShotModel;
use App\Services\Shoot\ShootService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShootController extends Controller {
    public function shoot(int $id, ShootRequest $request): JsonResponse {
        $user = Auth::user();
        extract($request->post());

        if (ShootService::isVisibleCell($x, $y, $user->shots)) {
            return response()->json([
                'success' => false,
                'error'   => 400,
                'message' => 'Unable to shoot here',
            ]);
        }

        $shootResult = ShootService::shoot($x, $y, $user->ships, $user->shots);

        extract($request->post());
        $shot          = new ShotModel();
        $shot->x       = $x;
        $shot->y       = $y;
        $shot->game_id = $id;
        $shot->user_id = $user->id;
        $shot->saveOrFail();

        $shots = $user->shots;

        if (isset($shootResult)) {
            $total_health = array_reduce($user->ships, function ($carry, $item) use ($shots) {
                $carry += ShootService::shipHealth($item, $shots);
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
                    $shot          = new ShotModel();
                    $shot->y       = $i;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;

                    if ($user->shots->where('x', $ship->x - 1)->firstWhere('y', $i)
                        && ($ship->x - 1) >= 0
                        && $i >= 0
                        && $i <= 9
                    ) {
                        $shot->x = $ship->x - 1;
                        $shot->saveOrFail();
                    }

                    if ($user->shots->where('x', $ship->x + 1)->firstWhere('y', $i)
                        && ($ship->x + 1) <= 9
                        && $i >= 0
                        && $i <= 9
                    ) {
                        $shot->x = $ship->x + 1;
                        $shot->saveOrFail();
                    }
                }
                if ($user->shots->where('x', $ship->x)->firstWhere('y', $ship->y - 1) && ($ship->y - 1) >= 0) {
                    $shot          = new ShotModel();
                    $shot->x       = $ship->x;
                    $shot->y       = $ship->y - 1;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;
                    $shot->saveOrFail();
                }
                if ($user->shots->where('x', $ship->x)->firstWhere('y', $ship->y + 1) && ($ship->y + 1) <= 9) {
                    $shot          = new ShotModel();
                    $shot->x       = $ship->x;
                    $shot->y       = $ship->y + 1;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;
                    $shot->saveOrFail();
                }
                break;
            case 'horizontal':
                for ($j = $ship->x - 1; $j <= $ship->x + $ship->size; $j++) {
                    $shot          = new ShotModel();
                    $shot->x       = $j;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;

                    if ($user->shots->where('y', $ship->y - 1)->firstWhere('x', $j)
                        && ($ship->y - 1) >= 0
                        && $j >= 0
                        && $j <= 9
                    ) {
                        $shot->y = $ship->y - 1;
                        $shot->saveOrFail();
                    }

                    if ($user->shots->where('y', $ship->y + 1)->firstWhere('x', $j)
                        && ($ship->y + 1) <= 9
                        && $j >= 0
                        && $j <= 9
                    ) {
                        $shot->y = $ship->y + 1;
                        $shot->saveOrFail();
                    }
                }
                if ($user->shots->where('y', $ship->y)->firstWhere('x', $ship->x - 1) && ($ship->x - 1) >= 0) {
                    $shot          = new ShotModel();
                    $shot->y       = $ship->y;
                    $shot->x       = $ship->x - 1;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;
                    $shot->saveOrFail();
                }
                if ($user->shots->where('y', $ship->y)->firstWhere('x', $ship->x + 1) && ($ship->x + 1) <= 9) {
                    $shot          = new ShotModel();
                    $shot->y       = $ship->y;
                    $shot->x       = $ship->x + 1;
                    $shot->game_id = $user->game->id;
                    $shot->user_id = $user->id;
                    $shot->saveOrFail();
                }
                break;
        }
    }
}
