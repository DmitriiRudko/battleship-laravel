<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ChatController extends Controller {
    public function loadMessages(ApiRequest $request): JsonResponse {
        $timeNow = time();
        $user    = Auth::user();

        if ($request->get('lastTime') === 'false') {
            $messages = $user->game->messages;
        } else {
            $messages = $user->game->scopeMessages($request->get('lastTime'));
        }

        return response()->success(ChatResource::make($messages));
    }

    public function sendMessage(int $id, MessageRequest $request): JsonResponse {
        $user = Auth::user();
        Message::newMessage($id, $user->id, $request->post('message'));

        return response()->json(['success' => true]);
    }
}
