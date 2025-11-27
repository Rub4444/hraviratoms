<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicInvitationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');


Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*');


Route::get('/i/{slug}', [PublicInvitationController::class, 'show'])
    ->name('invitation.public.show');
