<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Rutas de autenticación
Route::controller(AuthController::class)->group(function () {
    
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');

    Route::post('/invalidate-2fa', 'invalidate2FA')->name('invalidate.2fa');
});


Route::middleware('auth')->group(function () {
    Route::get('/welcome', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/', function () {
        return view('welcome');
    });
});

// 🔹 Manejo de rutas no encontradas
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

