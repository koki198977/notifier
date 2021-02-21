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

Route::get('/', function(){ return "welcome to API";});
// Route::post('/', 'ExampleController@index');
Route::get('/pdf', 'ExampleController@test');
Route::post('/pre_cuenta', 'VoucherController@preCuenta');
Route::post('/solicita_ticket', 'VoucherController@solicitaTicket');
Route::post('/solicita_happy', 'VoucherController@solicitaHappy');
Route::post('/solicita_boleta_electronica', 'VoucherController@solicitaElectronica');
