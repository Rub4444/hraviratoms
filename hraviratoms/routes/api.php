<?php
use App\Http\Controllers\Api\InvitationTemplateController;
use App\Http\Controllers\Api\InvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/templates', [InvitationTemplateController::class, 'index']);
Route::get('/templates/{key}', [InvitationTemplateController::class, 'show']);

Route::get('/invitations', [InvitationController::class, 'index']);
Route::post('/invitations', [InvitationController::class, 'store']);
Route::get('/invitations/{invitation}', [InvitationController::class, 'show']);
Route::put('/invitations/{invitation}', [InvitationController::class, 'update']);
Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy']);

