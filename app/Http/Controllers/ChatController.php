<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Models\MessageModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller {
    public function loadMessages(ApiRequest $request): JsonResponse {
        $timeNow = time();
        $user    = Auth::user();

        if ($request->get('lastTime')) {
            $messages = $user->game->scopeMessages($request->get('lastTime'));
        } else {
            $messages = $user->game->messages;
        }

        $messages = array_map(function ($message) use ($user) {
            return [
                'my'      => $message->user_id === $user->id,
                'time'    => strtotime($message->time),
                'message' => $message->message,
            ];
        }, iterator_to_array($messages));

        $messages = [
            'messages' => $messages,
            'lastTime' => $timeNow,
            'success'  => true,
        ];

        return response()->json($messages);
    }

    public function sendMessage($id, ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $message          = new MessageModel();
        $message->game_id = $id;
        $message->user_id = $user->id;
        $message->message = mb_strimwidth($request->post('message'), 0, MessageModel::MESSAGE_MAX_LEN);
        $message->saveOrFail();

        return response()->json(['success' => true]);
    }
}
