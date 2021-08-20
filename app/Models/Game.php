<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use phpDocumentor\Reflection\Types\Self_;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property int|null $status
 * @property int|null $turn
 * @property int $initiator_id
 * @property int|null $invited_id
 * @property-read \App\Models\User $initiator
 * @property-read \App\Models\User|null $invited
 * @property-read Collection|\App\Models\Message[] $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Game query()
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereInitiatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereInvitedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Game whereTurn($value)
 * @mixin \Eloquent
 */
class Game extends Model {
    use HasFactory;

    protected $table = 'games';

    public const GAME_HAS_NOT_BEGUN_STATUS = 1;

    public const GAME_HAS_BEGUN_STATUS = 2;

    public const GAME_OVER_STATUS = 3;

    public static function newGame(User $initiator, User $invited): self {
        $game               = self::getModel();
        $game->initiator_id = $initiator->id;
        $game->invited_id   = $invited->id;
        $game->turn         = [$initiator->id, $invited->id][random_int(0, 1)];
        $game->saveOrFail();

        return $game;
    }

    public function gameOver(): void {
        $this->status = self::GAME_OVER_STATUS;
        $this->saveOrFail();
    }

    public function startGame(): void {
        $this->status = self::GAME_HAS_BEGUN_STATUS;
        $this->saveOrFail();
    }

    public function switchTurn(): void {
        switch ($this->turn) {
            case $this->initiator->id:
                $this->turn = $this->invited->id;
                break;
            case $this->invited->id:
                $this->turn = $this->initiator->id;
                break;
        }
        $this->saveOrFail();
    }

    public function invited(): BelongsTo {
        return $this->belongsTo(User::class, 'invited_id');
    }

    public function initiator(): BelongsTo {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function messages(): HasMany {
        return $this->hasMany(Message::class, 'game_id');
    }

    public function rangeMessages(int $timestamp): Collection {
        return $this->messages()->where('time', '>', date('Y-m-d H:i:s', $timestamp))->get();
    }
}
