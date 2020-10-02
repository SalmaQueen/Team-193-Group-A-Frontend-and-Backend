<?php

use App\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
//https://github.com/safaricom/mpesa-php-sdk
*/

Auth::routes();

Route::get('/', function () { return view('welcome'); });
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/pay', 'MpesaC2bController');
Route::resource('/subscribe', 'SubscriptionController');
Route::resource('/status', 'TransactionStatusController');
Route::resource('/callback', 'MpesaCallbackController');
Route::get('/b2c', 'TransactionStatusController@b2c');
Route::get('/approve/{vehicle_registration_number}/{user_phone_number}', 'TransactionStatusController@approve');
Route::resource('/mails', 'EmailsController');



Route::group(['middleware'=>'admin'], function(){
    Route::resource("admin","AdminSaccoController");
    Route::get('/saccos', 'AdminSaccoController@saccos')->name('admin.saccos');
    Route::resource('/roles', 'AdminRolesController');
    Route::resource('/users', 'AdminUsersController');
    Route::resource('/admin/actions', 'AdminActionsController');
});

Route::group(['middleware'=>'sacco'], function(){
    Route::resource('/subscriptions', 'SaccoSubscriptionsController');
    Route::resource("sacco","SaccoVehicleController");
    Route::get('/vehicles', 'SaccoVehicleController@vehicles')->name('sacco.vehicles');
    Route::resource('/vehicle/action', 'SaccoVehicleActionsController');
    Route::get('/subscribers', 'SaccoSubscriptionsController@subscribers')->name('sacco.subscribers');
    Route::get('/payments', 'SaccoSubscriptionsController@payments')->name('sacco.payments');
});
