<?php

namespace App\Http\Resources;

use App\Models\Game;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource {
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    /** @var Game */
    public $resource;

    public function toArray($request): array {
        return [
            'id'      => $this->resource->id,
            'code'    => $this->resource->initiator->code,
            'invited' => $this->resource->invited->code,
        ];
    }
}
