<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\DiscordWidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/check-registration', [BotController::class, 'checkRegistration'])->middleware('token.valid');
Route::post('/user/ban', [BotController::class, 'banUser'])->middleware('token.valid');
Route::post('/user/unban', [BotController::class, 'unbanUser'])->middleware('token.valid');
Route::post('/user/nickname-color', [BotController::class, 'getUserNicknameColor'])->middleware('token.valid');
Route::post('/items/users-with-item', [BotController::class, 'getUsersWithItem'])->middleware('token.valid');


// Route::get('/discord-widget', [DiscordWidgetController::class, 'index'])->name('discord-widget');
