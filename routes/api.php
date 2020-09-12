<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->middleware(['api'])->group(function ($router){
    Route::post('login'  , 'AuthController@login');
    Route::post('register' , 'AuthController@register');
    Route::get('me' , 'AuthController@me');
    Route::post('logout'  , 'AuthController@logout');
    Route::post('refresh' , 'AuthController@refresh');
});

Route::prefix('threads')->group(function ($ROUTER){
    Route::get('/' , 'ThreadController@index');
    Route::post('/' , 'ThreadController@store');
});


