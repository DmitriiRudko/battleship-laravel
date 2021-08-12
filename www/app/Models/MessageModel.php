<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MessageModel
 *
 * @property int $id
 * @property string|null $message
 * @property int $game_id
 * @property string|null $time
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereGameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MessageModel whereUserId($value)
 * @mixin \Eloquent
 */
class MessageModel extends Model
{
    use HasFactory;

    public const MESSAGE_MAX_LEN = 250;

    protected $table = 'messages';
}
