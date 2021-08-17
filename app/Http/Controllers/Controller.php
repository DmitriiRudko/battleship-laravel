<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title = "Battleships API Documentation",
 *     version = "1.0.0",
 * )
 * @OA\Tag(
 *     name = "API",
 *     description = "Api methods",
 * )
 * @OA\Server(
 *     description = "Laravel API Swagger",
 *     url= "http://rudko-laravel/api"
 * )
 */

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

}
