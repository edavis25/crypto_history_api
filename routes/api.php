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
# /{exchange}/pairs (listing of available pairs)
# /{exchange}/pair (query for specific pair)
# /{exchange}/last price
# /{exchange}/
Route::get('/', function (Request $request) {
    return 'good job';
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
