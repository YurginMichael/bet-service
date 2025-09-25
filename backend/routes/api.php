<?php

declare(strict_types=1);

use App\Http\Api\Actions\Auth\Login as LoginAction;
use App\Http\Api\Actions\Auth\Logout as LogoutAction;
use App\Http\Api\Actions\Auth\Register as RegisterAction;
use App\Http\Api\Actions\Bets\Create as CreateBetAction;
use App\Http\Api\Actions\Bets\GetList as GetBetsAction;
use App\Http\Api\Actions\Events\GetItem as GetEventAction;
use App\Http\Api\Actions\Events\GetList as GetEventsAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginAction::class);
Route::post('/register', RegisterAction::class);

Route::get('/events', GetEventsAction::class);
Route::get('/events/{id}', GetEventAction::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', LogoutAction::class);

    Route::middleware(['rate.limit.bets', 'idempotency'])->group(function () {
        Route::post('/bets', CreateBetAction::class);
    });

    Route::get('/bets', GetBetsAction::class);
});

Route::middleware(['auth:sanctum', 'hmac.verify'])->group(function () {
    Route::post('/bets/external', CreateBetAction::class);
});
