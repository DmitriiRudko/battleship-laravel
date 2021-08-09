<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\ShipModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel query()
 * @mixin \Eloquent
 */
class ShipModel extends Model {
    use HasFactory;

    public function placeShip(int $gameId, int $playerId, int $size, int $number, string $orientation, int $x, int $y): void {
        DB::table('warships')->insert([
            'game_id' => $gameId,
            'user_id' => $playerId,
            'size' => $size,
            'number' => $number,
            'orientation' => $orientation,
            'x' => $x,
            'y' => $y,
        ]);
    }

    public function removeShip(int $gameId, int $playerId, int $size, int $number): void {
        DB::table('warships')->insert([
            'game_id' => $gameId,
            'user_id' => $playerId,
            'size' => $size,
            'number' => $number,
        ]);
    }
}
