<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StartController;
use App\Http\Controllers\PlaceShipController;
use App\Http\Controllers\ClearFieldController;
use App\Http\Controllers\UserReadyController;
use App\Http\Controllers\ChatController;

Route::post('/start', [StartController::class, 'newGame']);

Route::post('/place-ship/{id}/{code}', [PlaceShipController::class, 'action']);

Route::post('/clear-field/{id}/{code}', [ClearFieldController::class, 'clearField']);

Route::post('/ready/{id}/{code}', [UserReadyController::class, 'getUserReady']);

Route::get('/chat-load/{id}/{code}', [ChatController::class, 'loadMessages']);

Route::post('/chat-send/{id}/{code}', [ChatController::class, 'sendMessage']);

