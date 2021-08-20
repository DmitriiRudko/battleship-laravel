<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    /** @var Message */
    public $resource;

    public function toArray($request): array {
        $user = Auth::user();

        return [
            'messages' => $this->map(function ($message) use ($user) {
                return [
                    'my'      => $message->user_id === $user->id,
                    'time'    => strtotime($message->time),
                    'message' => $message->message,
                ];
            }),
            'lastTime' => time(),
        ];
    }
}
