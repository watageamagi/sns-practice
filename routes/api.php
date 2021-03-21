<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify');

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => ['auth:api']], function() {

    Route::get('user', function () {
        return Auth::user()->makeVisible('api_token');
    });

    Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

    Route::post('/logout', 'Auth\LoginController@logout');

});

Route::prefix('admin')->group(function () {
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/user/getAuth', function() {
            return Auth::user();
        });

        Route::prefix('user')->group(function () {
            Route::get('list', 'Admin\UserController@users');
            Route::get('{id}', 'Admin\UserController@detail');
            Route::patch('/', 'Admin\UserController@update');
        });

        // メンテナンスモード
        Route::prefix('maintenance')->group(function() {
            Route::get('/', 'Admin\MaintenanceController@index');
            Route::post('/change', 'Admin\MaintenanceController@change');
            Route::post('/', 'Admin\MaintenanceController@addIpAddress');
            Route::delete('/{id}', 'Admin\MaintenanceController@deleteIpAddress');
        });

        Route::get('/logout', 'Admin\LoginController@logout');
    });
});
