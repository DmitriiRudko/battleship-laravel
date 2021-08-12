<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\UserModel
 *
 * @property int $id
 * @property string $code
 * @property int|null $ready
 * @property-read \App\Models\GameModel|null $gameByInitiator
 * @property-read \App\Models\GameModel|null $gameByInvited
 * @property-read \App\Models\GameModel $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ShipModel[] $ships
 * @property-read int|null $ships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ShotModel[] $shots
 * @property-read int|null $shots_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereReady($value)
 * @mixin \Eloquent
 */
class UserModel extends Model {
    use HasFactory;

    protected $table = 'users';

    public const NOT_READY_STATUS = 0;

    public const READY_STATUS = 1;

    public function getGameAttribute(): GameModel {
        $result = array_filter([$this->gameByInitiator, $this->gameByInvited]);

        return array_shift($result);
    }

    public function enemy(): ?BelongsTo {
        switch ($this->id) {
            case $this->game->invited_id:
                return $this->game->initiator();
            case $this->game->initiator_id:
                return $this->game->invited();
            default:
                return null;
        }
    }

    public function gameByInitiator(): HasOne {
        return $this->hasOne(GameModel::class, 'initiator_id');
    }

    public function gameByInvited(): HasOne {
        return $this->hasOne(GameModel::class, 'invited_id');
    }

    public function ships(): HasMany {
        return $this->hasMany(ShipModel::class, 'user_id');
    }

    public function shots(): HasMany {
        return $this->hasMany(ShotModel::class, 'user_id');
    }
}
