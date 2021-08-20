<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ship
 *
 * @property int $id
 * @property int $game_id
 * @property int $size
 * @property int $number
 * @property int|null $x
 * @property int|null $y
 * @property string|null $orientation
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ship whereY($value)
 * @mixin \Eloquent
 */
class Ship extends Model {
    use HasFactory;

    protected $table = 'warships';

    public static function newShip(int $id, int $userId, int $x, int $y, int $size, int $number, string $orientation): self {
        $shipModel              = new self();
        $shipModel->game_id     = $id;
        $shipModel->user_id     = $userId;
        $shipModel->x           = $x;
        $shipModel->y           = $y;
        $shipModel->size        = $size;
        $shipModel->number      = $number;
        $shipModel->orientation = $orientation;
        $shipModel->saveOrFail();

        return $shipModel;
    }

}
