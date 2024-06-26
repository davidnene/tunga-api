<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProfileController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// API Routes 
Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::group([
    'middleware' => ['auth:api']
], function() {
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('refresh', [ApiController::class, 'refreshToken']);
    Route::get('logout', [ApiController::class, 'logout']);
    Route::apiResource('profiles', ProfileController::class, [
        'only' => ['index', 'show', 'store', 'update', 'destroy'],
       ]);
});