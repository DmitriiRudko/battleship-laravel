<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StatusResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($info) {
        $user = Auth::user();

        return [
            'fieldMy'    => $this['fieldMy'],
            'fieldEnemy' => $this['fieldEnemy'],
            'game'       => [
                'id'      => $user->game->id,
                'invite'  => $user->game->invited->code,
                'status'  => $user->game->status,
                'myTurn'  => $user->id === $user->game->turn,
                'meReady' => (bool)$user->ready,
            ],
            'success'    => true,
            'usedPlaces' => $user->ships->map(fn($ship) => $ship->size . '-' . $ship->number),//array_map(fn($ship) => $ship->size . '-' . $ship->number, iterator_to_array($user->ships)),
        ];
    }
}
