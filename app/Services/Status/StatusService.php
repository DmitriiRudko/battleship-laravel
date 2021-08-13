<?php

namespace App\Services\Status;

class StatusService {
    public static function getFieldInfo($myShips, $myShots, $enemyShips, $enemyShots) {
        $fieldMy = array_fill(0, 10, array_fill(0, 10, [
            0 => 'empty',
            1 => 0,
        ]));

        foreach ($myShips as $ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    for ($i = $ship->y; $i < $ship->y + $ship->size; $i++) {
                        $fieldMy[$i][$ship->x][0] = $ship->size . '-' . $ship->number;
                    }
                    break;
                case 'horizontal':
                    for ($j = $ship->x; $j < $ship->x + $ship->size; $j++) {
                        $fieldMy[$ship->y][$j][0] = $ship->size . '-' . $ship->number;
                    }
                    break;
            }
        }

        foreach ($myShots as $shot) {
            $fieldMy[$shot->y][$shot->x][1] = 1;
        }

        $fieldEnemy = array_fill(0, 10, array_fill(0, 10, [
            0 => 'empty',
            1 => 0,
        ]));

        foreach ($myShots as $shot) {
            $fieldEnemy[$shot->y][$shot->x][1] = 1;
        }

        foreach ($enemyShips as $ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    for ($i = $ship->y; $i < $ship->y + $ship->size; $i++) {
                        $fieldEnemy[$i][$ship->x][0] =
                            $fieldEnemy[$i][$ship->x][1] ? $ship->size . '-' . $ship->number : 'hidden';
                    }
                    break;
                case 'horizontal':
                    for ($j = $ship->x; $j < $ship->x + $ship->size; $j++) {
                        $fieldEnemy[$ship->y][$j][0] =
                            $fieldEnemy[$ship->y][$j][1] ? $ship->size . '-' . $ship->number : 'hidden';
                    }
                    break;
            }
        }

        return [
            'fieldMy'    => $fieldMy,
            'fieldEnemy' => $fieldEnemy,
        ];
    }
}
