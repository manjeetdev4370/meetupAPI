<?php

use Illuminate\Http\Request;
//use App\Http\Controllers\Auth\RegisterController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'APILoginController@login');

Route::middleware(['api','api_auth'])->post('/user/register', 'Auth\RegisterController@createParticipants')->name('apiUserRegistration');

Route::middleware(['api','api_auth'])->get('/user/details', 'Auth\RegisterController@getUsers')->name('apiUserDetails');

Route::middleware(['api','api_auth'])->put('/user/update/{id}', 'Auth\RegisterController@updateUsers')->name('apiUserUpdate');
