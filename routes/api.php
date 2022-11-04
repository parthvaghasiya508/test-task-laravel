<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['auth:sanctum']], function () { 
    Route::post('add-money', [UserController::class, 'addMoney']);
    Route::post('buy-cookie', [UserController::class, 'buyCookie']);
});

Route::post("login", [UserController::class, "login"]);
