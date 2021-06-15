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

# Todo route ideas:
# /{exchange}/last price

Route::get('/exchanges', 'ExchangeController@index')
    ->name('exchanges');

Route::get('/{exchange}/pairs', 'ExchangePairController@index')
    ->name('exchange.pairs');

Route::get('/{exchange}/pairs/{pair}', 'ExchangePairController@show')
    ->name('exchange.pairs.show');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
