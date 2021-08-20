<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path= "/clear-field/{id}/{code}",
 *     operationId = "Clear field",
 *     tags= {"API"},
 *     summary = "Removes all ships from the field",
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
 *          description= "Successfully cleared",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="boolean", example="true"),
 *          ),
 *     ),
 * ),
 */

class ClearFieldController extends Controller {
    public function clearField(): JsonResponse {
        $user = Auth::user();

        foreach ($user->ships as $ship) {
            $ship->delete();
        }

        return response()->success();
    }
}
