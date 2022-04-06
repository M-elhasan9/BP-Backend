<?php


use App\Http\Controllers\UserApiController;
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
    ->get('/user',[UserApiController::class,'getUser']);

Route::post('user/sendCode',[UserApiController::class,'sendCode']);
Route::post('user/login',[UserApiController::class,'login']);

Route::middleware('auth:sanctum')->post('user/report',[UserApiController::class,'addReport']);
Route::middleware('auth:sanctum')->post('user/addSubscribe',[UserApiController::class,'addSubscribe']);
