<?php

use Illuminate\Http\Request;

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

// List All Orders
Route::get('orders','OrderController@index');

//Create New Order
Route::post('orders','OrderController@store');

//Update Existing Order Status
Route::patch('orders/{id}','OrderController@update');