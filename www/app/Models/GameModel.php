<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameModel extends Model {
    use HasFactory;

    public function newGame(int $creatorId, int $invitedId): int {
        $this->creatorId = $creatorId;
        $this->invitedId = $invitedId;
        $this->save();
        return $this->id;
    }
}
