<?php

namespace App\Models;

use GuzzleHttp\Psr7\Message;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\GameModel
 *
 * @property int $id
 * @property int|null $status
 * @property string|null $turn
 * @property int $initiator_id
 * @property int|null $invited_id
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel whereInitiatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel whereInvitedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GameModel whereTurn($value)
 * @mixin Eloquent
 */
class GameModel extends Model {
    use HasFactory;

    protected $table = 'games';

    public const GAME_HAS_BEGUN_STATUS = 2;

    public const GAME_OVER_STATUS = 3;

    public function invited() {
        return $this->belongsTo(UserModel::class, 'invited_id');
    }

    public function initiator() {
        return $this->belongsTo(UserModel::class, 'initiator_id');
    }

    public function messages() {
        return $this->hasMany(MessageModel::class, 'game_id')->orderBy('time', 'asc');
    }

    public function scopeMessages($timestamp) {
        return $this->messages()->where('time', '>', date('Y-m-d h:i:s', $timestamp))->get();
    }
}
