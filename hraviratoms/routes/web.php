<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InvitationTemplateController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\InvitationRsvpController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DemoInvitationController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная LoveLeaf
Route::get('/', [HomeController::class, 'index'])
    ->name('landing');

// Публичные приглашения + RSVP
Route::get('/i/{slug}', [PublicInvitationController::class, 'show'])
    ->name('invitation.public.show');

Route::post('/i/{slug}/rsvp', [PublicInvitationController::class, 'submitRsvp'])
    ->name('invitation.public.rsvp');

Route::get('/demo/{key}', [DemoInvitationController::class, 'show'])
    ->name('demo.show');

// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.perform');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

// Всё, что ниже — только для залогиненных
Route::middleware('auth')->group(function () {

    // Vue SPA Admin
    Route::get('/admin/{any?}', function () {
        return view('admin');
    })->where('any', '.*');

    // Admin API (для Vue) — доступен только после логина
    Route::prefix('api')->group(function () {

        Route::middleware('admin')->group(function ()
        {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::put('/users/{user}', [UserController::class, 'update']);
            Route::delete('/users/{user}', [UserController::class, 'destroy']);

            Route::get('/templates', [InvitationTemplateController::class, 'index']);
            Route::get('/templates/{key}', [InvitationTemplateController::class, 'show']);

            Route::get('/invitations', [InvitationController::class, 'index']);
            Route::post('/invitations', [InvitationController::class, 'store']);
            Route::get('/invitations/{invitation}', [InvitationController::class, 'show']);
            Route::put('/invitations/{invitation}', [InvitationController::class, 'update']);
            Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy']);
        });

        // RSVP-статистика
        Route::get('/invitations/{invitation}/rsvps', [InvitationRsvpController::class, 'index']);
    });

});
