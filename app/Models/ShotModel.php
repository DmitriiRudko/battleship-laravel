<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShotModel
 *
 * @property int $id
 * @property int|null $x
 * @property int|null $y
 * @property int $game_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShotModel whereY($value)
 * @mixin \Eloquent
 */
class ShotModel extends Model {
    use HasFactory;

    protected $table = 'steps';

    public static function newShot(int $x, int $y, int $gameId, int $userId): ShotModel {
        $shot          = new self();
        $shot->x       = $x;
        $shot->y       = $y;
        $shot->game_id = $gameId;
        $shot->user_id = $userId;
        $shot->saveOrFail();
        return $shot;
    }
}
