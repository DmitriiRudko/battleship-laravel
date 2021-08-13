<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\PlaceShipController;
use App\Http\Controllers\ClearFieldController;
use App\Http\Controllers\UserReadyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\StatusController;

Route::post('/start/', [StartController::class, 'newGame']);

Route::post('/place-ship/{id}/{code}/', [PlaceShipController::class, 'action'])->middleware('authenticated');

Route::post('/clear-field/{id}/{code}/', [ClearFieldController::class, 'clearField'])->middleware('authenticated');

Route::post('/ready/{id}/{code}/', [UserReadyController::class, 'getUserReady'])->middleware('authenticated');

Route::get('/chat-load/{id}/{code}/', [ChatController::class, 'loadMessages'])->middleware('authenticated');

Route::post('/chat-send/{id}/{code}/', [ChatController::class, 'sendMessage'])->middleware('authenticated');

Route::post('/status/{id}/{code}/', [StatusController::class, 'getGameStatus'])->middleware('authenticated');
