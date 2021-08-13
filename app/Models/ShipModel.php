<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShipModel
 *
 * @property int $id
 * @property int $game_id
 * @property int $size
 * @property int $number
 * @property int|null $x
 * @property int|null $y
 * @property string|null $orientation
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereOrientation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipModel whereY($value)
 * @mixin \Eloquent
 */
class ShipModel extends Model {
    use HasFactory;

    protected $table = 'warships';

    public static function newShip($id, $userId, $x, $y, $size, $number, $orientation): self {
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
