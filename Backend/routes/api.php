<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\VerificationController;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>['auth:sanctum']],function () {
    Route::get('/profile',[UserAuthController::class,'profile'])->name('profile');
    Route::get('/user/{id}',[UserAuthController::class,'show']);
    Route::put('/user/{id}',[UserAuthController::class,'update']);
    Route::delete('/user/{id}',[UserAuthController::class,'destroy']);
});

Route::controller(UserAuthController::class)->group(function () {
    Route::post('register','register')->name('register');
    Route::post ('login','login')->name('login');
    Route::post('logout','logout')
      ->middleware('auth:sanctum')->name('logout');
    
});


Route::controller(VerificationController::class)->group(function() {
    Route::post('/email/verify', 'notice')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');   
});