<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    /** @var User */
    public $resource;

    public function toArray($request): array {
        return [
            'enemyReady' => (bool)$this->enemy->ready,
        ];
    }
}
