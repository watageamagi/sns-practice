<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::group(['middleware' => 'guest'], function () {
    Route::post('login', 'Auth\LoginController@login');

    Route::post('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')
        ->name('verification.verify');

    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
});


//Auth::routes(['verify' => true]);

Route::get('/', function() {
    return response(view('app.index'))->header('Content-type', 'gzip');
});

Route::get('{path}', function () {
    return response(view('app.index'))->header('Content-type', 'gzip');
})->where('path', '^(?!.*admin).+$');
