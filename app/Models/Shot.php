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
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Shot whereY($value)
 * @mixin \Eloquent
 */
class Shot extends Model {
    use HasFactory;

    protected $table = 'steps';

    public static function newShot(int $x, int $y, int $gameId, int $userId): Shot {
        $shot          = new self();
        $shot->x       = $x;
        $shot->y       = $y;
        $shot->game_id = $gameId;
        $shot->user_id = $userId;
        $shot->saveOrFail();
        return $shot;
    }
}
