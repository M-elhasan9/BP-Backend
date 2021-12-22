<?php


use App\Http\Controllers\API\ReportApiController;
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

Route::middleware('auth:sanctum')
    ->get('/user',[\App\Http\Controllers\UserApiController::class,'getUser']);

Route::post('user/sendCode',[\App\Http\Controllers\UserApiController::class,'sendCode']);
Route::post('user/login',[\App\Http\Controllers\UserApiController::class,'login']);
