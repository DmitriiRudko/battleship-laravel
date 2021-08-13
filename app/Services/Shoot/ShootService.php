<?php

namespace App\Services\Shoot;

use App\Models\ShipModel;

class ShootService {
    public static function isVisibleCell(int $x, int $y, $shots): bool {
        $shot = $shots->where('x', $x)->firstWhere('y', $y);

        return !is_null($shot);
    }

    public static function shoot(int $x, int $y, $ships): ?ShipModel {
        $cell = null;
        foreach ($ships as $ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    $cell = ($y >= $ship->y && $y <= ($ship->y + $ship->size - 1)) ? $ship : $cell;
                    break;
                case 'horizontal':
                    $cell = ($x <= $ship->x && $x >= ($ship->x + $ship->size - 1)) ? $ship : $cell;
                    break;
            }
            if ($cell) break;
        }

        return $cell;
    }

    public static function shipHealth($ship, $shots): int {
        $shots = array_reduce(iterator_to_array($shots), function ($carry, $item) use ($ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    $carry += ($item->y >= $ship->y && $item->y <= ($ship->y + $ship->size - 1)) ? 1 : 0;
                    break;
                case 'horizontal':
                    $carry += ($item->x <= $ship->x && $item->x >= ($ship->x + $ship->size - 1)) ? 1 : 0;
                    break;
            }

            return $carry;
        }, 0);

        return $ship->size - $shots;
    }
}