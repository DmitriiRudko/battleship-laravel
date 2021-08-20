<?php

namespace App\Http\Controllers;


use App\Http\Resources\StatusResource;
use App\Services\Status\StatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/status/{id}/{code}",
 *     operationId = "Retrieve status",
 *     tags= {"API"},
 *     summary = "Retrieves game info",
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
 *              @OA\Property(
 *                  property="game",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example="9"),
 *                  @OA\Property(property="status", type="integer", example="1"),
 *                  @OA\Property(property="invite", type="string", example="aeGt81HUEn"),
 *                  @OA\Property(property="myTurn", type="boolean", example="true"),
 *                 @OA\Property(property="meReady", type="boolean", example="true"),
 *              ),
 *              @OA\Property(
 *                  property="fieldMy",
 *                  type="array",
 *                  @OA\Items(
 *                  ),
 *              ),
 *              @OA\Property(
 *                  property="fieldEnemy",
 *                  type="array",
 *                  @OA\Items(
 *                  ),
 *              ),
 *             @OA\Property(
 *                  property="usedPlaces",
 *                  type="array",
 *                  @OA\Items(
 *                  ),
 *              ),
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */
class StatusController {
    private StatusService $statusService;

    public function __construct(StatusService $statusService) {
        $this->statusService = $statusService;
    }

    public function getGameStatus(): JsonResponse {
        $user = Auth::user();

        $info               = $this->statusService->getFieldInfo($user->ships, $user->shots, $user->enemy->ships, $user->enemy->shots);
        $info['fieldMy']    = $this->statusService->transpose($info['fieldMy']);
        $info['fieldEnemy'] = $this->statusService->transpose($info['fieldEnemy']);

        return response()->success(StatusResource::make($info)->resolve());
    }
}
