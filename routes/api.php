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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('twitter-query/{search}', [\App\Http\Controllers\TwitterController::class, 'searchTweets']);
Route::get('twitter-query-all/{search}', [\App\Http\Controllers\TwitterController::class, 'searchAllTweets']);
Route::get('search-user-by-id/{id}', [\App\Http\Controllers\TwitterController::class, 'searchUserById']);
Route::get('search-user-by-username/{username}', [\App\Http\Controllers\TwitterController::class, 'searchUserByUsername']);