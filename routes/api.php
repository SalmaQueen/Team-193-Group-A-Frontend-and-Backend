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

Route::post("/approve_by_phone","TransactionStatusController@approve_by_phone");
Route::post("/approve_by_code","TransactionStatusController@approve_by_code");
Route::post("/pay_by_phone","MpesaC2bController@pay_by_phone");
Route::post("/load_vehicles","MpesaC2bController@load_vehicles");
Route::post("/load_payments","MpesaC2bController@load_payments");
Route::post('/c2bcallback', 'TransactionCallbackController@c2bcallback');
Route::post('/b2ccallback', 'TransactionCallbackController@b2ccallback');
Route::post('/ussd', 'TransactionUssdController@ussd');
Route::post('/load_wallet', 'TransactionStatusController@wallet_balance');
Route::post('/withdraw', 'TransactionStatusController@withdraw');
