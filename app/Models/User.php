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
 * @property-read \App\Models\Game|null $gameByInitiator
 * @property-read \App\Models\Game|null $gameByInvited
 * @property-read \App\Models\Game $game
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ship[] $ships
 * @property-read int|null $ships_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shot[] $shots
 * @property-read int|null $shots_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReady($value)
 * @mixin \Eloquent
 */
class User extends Model {
    use HasFactory;

    protected $table = 'users';

    public const NOT_READY_STATUS = 0;

    public const READY_STATUS = 1;

    public function getGameAttribute(): Game {
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

    public static function newUser(): self {
        $user       = self::getModel();
        $user->code = uniqid();
        $user->saveOrFail();
        return $user;
    }

    public function getReady() {
        $this->ready = self::READY_STATUS;
        $this->saveOrFail();
    }

    public function gameByInitiator(): HasOne {
        return $this->hasOne(Game::class, 'initiator_id');
    }

    public function gameByInvited(): HasOne {
        return $this->hasOne(Game::class, 'invited_id');
    }

    public function ships(): HasMany {
        return $this->hasMany(Ship::class, 'user_id');
    }

    public function shots(): HasMany {
        return $this->hasMany(Shot::class, 'user_id');
    }
}
