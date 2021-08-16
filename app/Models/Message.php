<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property string|null $message
 * @property int $game_id
 * @property string|null $time
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model {
    use HasFactory;

    public const MESSAGE_MAX_LEN = 250;

    protected $table = 'messages';

    public static function newMessage(int $gameId, int $userId, $text) {
        $message          = self::getModel();
        $message->game_id = $gameId;
        $message->user_id = $userId;
        $message->message = htmlspecialchars(mb_strimwidth($text, 0, self::MESSAGE_MAX_LEN));
        $message->saveOrFail();

        return $message;
    }
}
