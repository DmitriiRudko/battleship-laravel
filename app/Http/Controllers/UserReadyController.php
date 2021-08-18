<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/ready/{id}/{code}",
 *     operationId = "User ready",
 *     tags= {"API"},
 *     summary = "Get user ready",
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
 *     @OA\Response(
 *          response="200",
 *          description= "Chat loaded successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */
class UserReadyController extends Controller {
    public function getUserReady(ApiRequest $request): JsonResponse {
        $user = Auth::user();

        $user->getReady();

        if ($user->enemy->ready) {
            $user->game->startGame();
        }

        return response()->success(UserResource::make($user)->resolve());
    }
}
