<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Get(
 *     path= "/chat-load/{id}/{code}",
 *     operationId = "Load messages",
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
 *          description= "Chat loaded successfully",
 *          @OA\JsonContent(
 *              @OA\Property(
 *                  property="messages",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="my", type="boolean", example="true"),
 *                      @OA\Property(property="time", type="integer", example="1495517526"),
 *                      @OA\Property(property="message", type="string", example="Hello, World!"),
 *                  ),
 *              ),
 *              @OA\Property(property="lastTime", type="integer", example="61122735b150c"),
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 *
 * @OA\Post(
 *     path= "/chat-send/{id}/{code}",
 *     operationId = "Send message",
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
 *          @OA\MediaType(
 *              mediaType="multipart/form-data",
 *              @OA\Schema(
 *                  required={"message"},
 *                  @OA\Property(
 *                      property="message",
 *                      type="string",
 *                      description="Message to send (250 characters limit)",
 *                  ),
 *              ),
 *          ),
 *     ),
 *     @OA\Response(
 *          response="200",
 *          description= "Message sent successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */
class ChatController extends Controller {
    public function loadMessages(Request $request): JsonResponse {
        $user = Auth::user();

        if ($request->get('lastTime') === 'false') {
            $messages = $user->game->messages;
        } else {
            $messages = $user->game->rangeMessages($request->get('lastTime'));
        }

        return response()->success(ChatResource::make($messages)->resolve());
    }

    public function sendMessage(int $id, MessageRequest $request): JsonResponse {
        $user = Auth::user();
        Message::newMessage($id, $user->id, $request->post('message'));

        return response()->success();
    }
}
