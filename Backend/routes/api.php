<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserInfoController;
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



Route::group(['middleware'=>['auth:api']],function () {
    Route::get('/profile',[UserAuthController::class,'profile'])->name('profile');
    Route::get('/user/{name}',[UserAuthController::class,'show'])->middleware('role:admin');
    Route::put('/update/{id}',[UserAuthController::class,'update'])->name('update');
    Route::delete('/user/{id}',[UserAuthController::class,'destroy'])->middleware('role:admin');
    Route::get('/activity',[UserAuthController::class,'activity'])->name('activity');
    Route::get('/info',[UserInfoController::class,'info'])->name('info');
    Route::post('/info',[UserInfoController::class,'store'])->name('store');

    Route::get('/all-activity',[UserAuthController::class,'all_activity'])->name('all_activity')->middleware('role:admin');

});

Route::controller(UserAuthController::class)->group(function () {
    Route::post('/register','register')->name('register');

    Route::post ('/login','login')->name('login');
    Route::post('/logout','logout')->name('logout');
    Route::post('/refresh','refresh')->name('refresh');
    Route::get('/forget-password', 'passwordForm')->name('password.request');
    Route::post('/forget-password', 'submitForm')->name('password.email'); 
    Route::get('/reset-password/{token}', 'resetForm')->name('password.reset');
    Route::post('/reset-password',  'submitReset')->name('password.update');
    
});

Route::controller(VerificationController::class)->group(function() {
    Route::post('/email/verify', 'notice')->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/resend', 'resend')->middleware('auth')->name('verification.resend');   
});