<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\UserModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property int|null $ready
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserModel whereReady($value)
 * @property-read \App\Models\GameModel|null $initiator
 * @property-read \App\Models\GameModel|null $invited
 */
class UserModel extends Model {
    use HasFactory;

    protected $table = 'users';


    public function getUserInfoByCode($code): array {
        $userInfo = (array)DB::table('users')->where('code', $code)->first();

        return $userInfo;
    }

    public function getGameAttribute() {
        $result = array_filter([$this->gameByInitiator, $this->gameByInvited]);
        return array_shift($result);
    }

    public function gameByInitiator() {
        return $this->hasOne(GameModel::class, 'initiator_id');
    }

    public function gameByInvited() {
        return $this->hasOne(GameModel::class, 'invited_id');
    }

    public function ships() {
        return $this->hasMany(ShipModel::class, 'user_id');
    }
}
