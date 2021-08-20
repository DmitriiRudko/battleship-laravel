<?php

namespace App\Services\Shoot;

use App\Models\Ship;
use App\Models\Shot;
use Illuminate\Support\Facades\Auth;
use function React\Promise\reduce;

class ShootService {
    public function isVisibleCell(int $x, int $y, $shots): bool {
        $shot = $shots->where('x', $x)->firstWhere('y', $y);

        return !is_null($shot);
    }

    public function shoot(int $x, int $y, $ships): ?Ship {
        $cell = null;
        foreach ($ships as $ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    $cell = ($y >= $ship->y && $y <= ($ship->y + $ship->size - 1)) && $ship->x === $x ? $ship : $cell;
                    break;
                case 'horizontal':
                    $cell = ($x >= $ship->x && $x <= ($ship->x + $ship->size - 1)) && $ship->y === $y ? $ship : $cell;
                    break;
            }
            if ($cell) break;
        }

        return $cell;
    }

    public function shipHealth($ship, $shots): int {
        $hits = $shots->reduce(function ($carry, $item) use ($ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    $carry += ($item->y >= $ship->y && $item->y <= ($ship->y + $ship->size - 1) && $ship->x === $item->x) ? 1 : 0;
                    break;
                case 'horizontal':
                    $carry += ($item->x >= $ship->x && $item->x <= ($ship->x + $ship->size - 1) && $ship->y === $item->y) ? 1 : 0;
                    break;
            }

            return $carry;
        }, 0);

        return $ship->size - $hits;
    }

    public function shootAroundShip($ship, $shots) {
        $i     = $ship->y - 1;
        $j     = $ship->x - 1;
        $user  = Auth::user();
        $shots = [];
        $dx    = [1, 0, -1, 0];
        $dy    = [0, 1, 0, -1];
        $field = array_fill(0, 10, array_fill(0, 10, 0));

        foreach ($shots as $shot) {
            $field[$shot->y][$shot->x] = 1;
        }

        switch ($ship->orientation) {
            case 'vertical':
                do {
                    if (!$field[$i][$j]) {
                        array_push($shots, [$i, $j]);
                    }
                  //  if ($i === )
                    $i += $dy[0];
                    $j += $dx[0];
                } while (($i !== $ship->x - 1) && ($j !== $ship->y - 1));
                break;
            case 'horizontal':
                break;
        }


        switch ($ship->orientation) {
            case 'vertical':
                for ($i = $ship->y - 1; $i <= $ship->y + $ship->size; $i++) {
                    if (!$user->shots->where('x', $ship->x - 1)
                            ->firstWhere('y', $i) && ($ship->x - 1) >= 0 && $i >= 0 && $i <= 9) {
                        Shot::newShot($ship->x - 1, $i, $user->game->id, $user->id);
                    }

                    if (!$user->shots->where('x', $ship->x + 1)
                            ->firstWhere('y', $i) && ($ship->x + 1) <= 9 && $i >= 0 && $i <= 9) {
                        Shot::newShot($ship->x + 1, $i, $user->game->id, $user->id);
                    }
                }

                if (!$user->shots->where('x', $ship->x)->firstWhere('y', $ship->y - 1) && ($ship->y - 1) >= 0) {
                    Shot::newShot($ship->x, $ship->y - 1, $user->game->id, $user->id);
                }

                if (!$user->shots->where('x', $ship->x)
                        ->firstWhere('y', $ship->y + $ship->size) && ($ship->y + $ship->size) <= 9) {
                    Shot::newShot($ship->x, $ship->y + $ship->size, $user->game->id, $user->id);
                }
                break;

            case 'horizontal':
                for ($j = $ship->x - 1; $j <= $ship->x + $ship->size; $j++) {
                    if (!$user->shots->where('y', $ship->y - 1)
                            ->firstWhere('x', $j) && ($ship->y - 1) >= 0 && $j >= 0 && $j <= 9) {
                        Shot::newShot($j, $ship->y - 1, $user->game->id, $user->id);
                    }

                    if (!$user->shots->where('y', $ship->y + 1)
                            ->firstWhere('x', $j) && ($ship->y + 1) <= 9 && $j >= 0 && $j <= 9) {
                        Shot::newShot($j, $ship->y + 1, $user->game->id, $user->id);
                    }
                }

                if (!$user->shots->where('y', $ship->y)->firstWhere('x', $ship->x - 1) && ($ship->x - 1) >= 0) {
                    Shot::newShot($ship->x - 1, $ship->y, $user->game->id, $user->id);
                }

                if (!$user->shots->where('y', $ship->y)
                        ->firstWhere('x', $ship->x + $ship->size) && ($ship->x + $ship->size) <= 9) {
                    Shot::newShot($ship->x + $ship->size, $ship->y, $user->game->id, $user->id);
                }
                break;
        }
    }

    public function isOver($ships, $shots): bool {
        $total_health = $ships->reduce(function ($carry, $item) use ($shots) {
            $carry += $this->shipHealth($item, $shots);

            return $carry;
        }, 0);

        return !$total_health;
    }
}
