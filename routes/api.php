<?php

use Illuminate\Http\Request;
// use Illuminate\Routing\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');

// Route::apiResource('/ceo', 'Api\CEOController')->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/ceo/{id}', 'Api\CEOController@show');
    Route::get('/ceo', 'Api\CEOController@index');
    Route::post('/ceo', 'Api\CEOController@store');
    Route::put('/ceo/{id}', 'Api\CEOController@update');
    Route::delete('/ceo/{id}', 'Api\CEOController@destroy');
});

