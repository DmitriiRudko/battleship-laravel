<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/chat-load/{id}/{code}",
 *     operationId = "Clear field",
 *     tags= {"API"},
 *     summary = "Loads a messages from database",
 *     @OA\Parameter (
 *          name= "id",
 *          in= "path",
 *          description= "Game id",
 *          required= true,
 *     ),
 *     @OA\Parameter (
 *          name= "code",
 *          in= "path",
 *          description= "The user's code",
 *          required= true,
 *     ),
 *     @OA\Parameter (
 *          name= "lastTime",
 *          in= "query",
 *          description= "Timestamp of the most recent message",
 *          required= false,
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description= "Successfully cleared",
 *          @OA\JsonContent(),
 *     ),
 * ),
 *
 * @OA\Post(
 *     path= "/chat-send/{id}/{code}",
 *     operationId = "Clear field",
 *     tags= {"API"},
 *     summary = "Sends a message to a user",
 *     @OA\Parameter (
 *          name= "id",
 *          in= "path",
 *          description= "Game id",
 *          required= true,
 *     ),
 *     @OA\Parameter (
 *          name= "code",
 *          in= "path",
 *          description= "The user's code",
 *          required= true,
 *     ),
 *     @OA\RequestBody(
 *          required=true,
 *          description="Pass user credentials",
 *          @OA\Schema(
 *              type="array",
 *              @OA\Items(type="string"),
 *         ),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description= "Successfully cleared",
 *          @OA\JsonContent(),
 *     ),
 * ),
 */
class ChatController extends Controller {
    public function loadMessages(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        if ($request->get('lastTime') === 'false') {
            $messages = $user->game->messages;
        } else {
            $messages = $user->game->scopeMessages($request->get('lastTime'));
        }

        return response()->success(ChatResource::make($messages)->resolve());
    }

    public function sendMessage(int $id, MessageRequest $request): JsonResponse {
        $user = Auth::user();
        Message::newMessage($id, $user->id, $request->post('message'));

        return response()->success();
    }
}
