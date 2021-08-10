<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\PlaceShipController;
use App\Http\Controllers\ClearFieldController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('/start', [StartController::class, 'newGame']);

Route::post('/place-ship/{id}/{code}', [PlaceShipController::class, 'action']);

Route::post('/clear-field/{id}/{code}', [ClearFieldController::class, 'action']);

