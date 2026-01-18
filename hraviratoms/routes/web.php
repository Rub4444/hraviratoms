<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicInvitationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\InvitationTemplateController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\InvitationRsvpController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\GuestController;
use Illuminate\Http\Request;
use App\Http\Controllers\PreviewInvitationController;
use App\Http\Controllers\DemoInvitationController;

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


/**
 * ✅ Заявка на создание приглашения
 * GET — форма, POST — отправка заявки
 */
Route::get('/request-invitation/{templateKey?}', [PublicInvitationController::class, 'showRequestForm'])
    ->name('invitation.request.form');

Route::post('/request-invitation', [PublicInvitationController::class, 'submitRequest'])
    ->name('invitation.request.submit');


// Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.perform');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


Route::get('/api/invitation-pricing', function () {
    return response()->json([
        'features' => config('invitation_pricing.features'),
    ]);
});


Route::post('/preview/invitation', [PreviewInvitationController::class, 'show']);

Route::get('/demo/preview/{template}', [DemoInvitationController::class, 'preview'])
    ->name('demo.preview');

Route::get('/demo/{key}', [DemoInvitationController::class, 'show'])
->name('demo.show');

Route::post('/demo/to-request', function (Request $request) {

    $data = json_decode($request->input('demo_payload'), true);

    // сохраняем во временную сессию
    session()->put('demo.invitation', $data);

    // редиректим на форму заявки
    return redirect()->route(
        'invitation.request.form',
        ['templateKey' => $data['template'] ?? null]
    );
});

Route::post('/api/demo/calculate-price', function (Request $request) {
    $data = $request->validate([
        'template_key' => 'required|exists:invitation_templates,key',
        'features' => 'array',
    ]);

    $template = \App\Models\InvitationTemplate::where('key', $data['template_key'])->firstOrFail();

    return response()->json([
        'price' => \App\Services\InvitationPriceCalculator::calculate(
            $template,
            $data['features'] ?? []
        )
    ]);
});

// Всё, что ниже — только для залогиненных
Route::middleware('auth')->group(function () {

    // Vue SPA Admin
    Route::get('/admin/{any?}', function () {
        return view('admin');
    })->where('any', '.*');

    // Admin API (для Vue) — доступен только после логина
    Route::prefix('api')->group(function () {
        
        Route::post('/invitations/calculate-price', function (Request $request) {
            $data = $request->validate([
                'template_id' => ['required', 'exists:invitation_templates,id'],
                'features'    => ['array'],
            ]);

            $template = \App\Models\InvitationTemplate::findOrFail($data['template_id']);

            return response()->json([
                'price' => \App\Services\InvitationPriceCalculator::calculate(
                    $template,
                    $data['features'] ?? []
                ),
            ]);
        });

        Route::get('/me', [UserController::class, 'me']);

        Route::get('/invitations', [InvitationController::class, 'index']);
        Route::get('/invitations/{invitation}/rsvps', [InvitationRsvpController::class, 'index']); // RSVP-статистика

        Route::put('/guests/{guest}', [GuestController::class, 'update']);
        Route::delete('/guests/{guest}', [GuestController::class, 'destroy']);

        Route::middleware('admin')->group(function ()
        {
            Route::get('/users', [UserController::class, 'index']);
            Route::post('/users', [UserController::class, 'store']);
            Route::put('/users/{user}', [UserController::class, 'update']);
            Route::delete('/users/{user}', [UserController::class, 'destroy']);

            Route::get('/templates', [InvitationTemplateController::class, 'index']);
            Route::get('/templates/{key}', [InvitationTemplateController::class, 'show']);

            Route::post('/invitations/{id}/gallery', [InvitationController::class, 'uploadGallery']);
            Route::delete('/invitations/{id}/gallery/{imageId}', [InvitationController::class, 'deleteGallery']);


            Route::post('/invitations', [InvitationController::class, 'store']);
            Route::get('/invitations/{id}', [InvitationController::class, 'show'])
                ->whereNumber('id');
            Route::put('/invitations/{id}', [InvitationController::class, 'update'])
                ->whereNumber('id');
            Route::delete('/invitations/{id}', [InvitationController::class, 'destroy'])
                ->whereNumber('id');

            // ✨ Новый эндпоинт: создать/привязать юзера по заявке
            Route::post('/invitations/{invitation}/create-user', [UserController::class, 'createFromInvitation']);

        });
    });

});
