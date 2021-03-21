<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('user', function () {
        return Auth::user()->makeVisible('api_token');
    });
    Route::post('logout', 'Admin\LoginController@logout');
});

Route::group(['middleware' => ['web-admin', 'guest:admin']], function () {
    Route::post('login', 'Admin\LoginController@login');
});

Route::get('/{path?}', function () {
    return response(view('admin.index'))->header('Content-type', 'gzip');
})->where('path', '.*');
