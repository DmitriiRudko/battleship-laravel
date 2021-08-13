<?php

namespace App\Services\PlaceShip;

use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;

class PlaceShipService {
    public function isShipValid(int $shipSize, int $shipNumber, int $x, int $y, string $orientation, int $userId, int $gameId, $ships): bool {
        return !$this->isShipExist($shipSize, $shipNumber, $ships, $userId, $gameId)
            && $this->isShipFitsTheField($shipSize, $x, $y, $orientation)
            && $this->isShipFitsOtherShips($shipSize, $x, $y, $orientation, $ships);
    }

    public function isShipExist(int $shipSize, int $shipNumber, $ships, $userId, $gameId): bool {
        $sameShip = $ships->where('game_id', $gameId)
            ->where('user_id', $userId)
            ->where('size', $shipSize)
            ->firstWhere('number', $shipNumber);

        return !is_null($sameShip);
    }

    public function isShipFitsTheField(int $shipSize, int $x, int $y, string $orientation): bool {
        $result = ($x >= 0) && ($y >= 0) && ($x <= 9) && ($y <= 9);
        switch ($orientation) {
            case 'horizontal':
                return ($x + $shipSize <= 10) && $result;
            case 'vertical':
                return ($y + $shipSize <= 10) && $result;
            default:
                return false;
        }
    }

    public function isShipFitsOtherShips(int $shipSize, int $x, int $y, string $orientation, $ships): bool {
        $field = array_fill(0, 10, array_fill(0, 10, 0));

        switch ($orientation) {
            case 'vertical':
                for ($i = $y; $i < $y + $shipSize; $i++) {
                    $field[$i][$x] = 1;
                }
                break;
            case 'horizontal':
                for ($j = $x; $j < $x + $shipSize; $j++) {
                    $field[$y][$j] = 1;
                }
                break;
        }

        foreach ($ships as $ship) {
            switch ($ship->orientation) {
                case 'vertical':
                    for ($i = $y - 1; $i <= $y + $shipSize; $i++) {
                        if (isset($field[$i][$x - 1]) || isset($field[$i][$x]) || isset($field[$i][$x + 1])) {
                            return false;
                        }
                    }
                    break;
                case 'horizontal':
                    for ($j = $x - 1; $j <= $x + $shipSize; $j++) {
                        if (isset($field[$y - 1][$j]) || isset($field[$y][$j]) || isset($field[$y + 1][$j])) {
                            return false;
                        }
                    }
                    break;
            }
        }

        return true;
    }
}