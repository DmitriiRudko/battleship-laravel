<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\MessageRequest;
use App\Models\MessageModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller {
    public function loadMessages(ApiRequest $request): JsonResponse {
        $timeNow = time();
        $user    = Auth::user();

        if ($request->get('lastTime') === 'false') {
            $messages = $user->game->messages;
        } else {
            $messages = $user->game->scopeMessages($request->get('lastTime'));
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

    public function sendMessage(int $id, MessageRequest $request): JsonResponse {
        $user = Auth::user();
        MessageModel::newMessage($id, $user->id, $request->post('message'));

        return response()->json(['success' => true]);
    }
}
