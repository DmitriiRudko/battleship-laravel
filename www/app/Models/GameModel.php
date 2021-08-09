<?php

namespace App\Models;

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

    public function newGame(int $initiatorId, int $invitedId): int {
        $turn = random_int(0, 1);
        $turn = [$initiatorId, $invitedId][$turn];

        $gameId = DB::table('games')->insertGetId([
            'initiator_id' => $initiatorId,
            'invited_id' => $invitedId,
            'turn' => $turn,
        ]);

        return $gameId;
    }
}
