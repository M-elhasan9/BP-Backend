<?php


use App\Http\Controllers\CameraApiController;
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
    ->get('/user', [UserApiController::class, 'getUser']);

Route::post('/user/sendCode', [UserApiController::class, 'sendCode']);


Route::post('/user/login', [UserApiController::class, 'login']);

//for camera
//Route::post('camera/addReport',[CameraApiController::class,'addReport']);


////


Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::post('/user/addUserSubscribe', [UserApiController::class, 'addUserSubscribe']);
    Route::post('/user/addUserReport', [UserApiController::class, 'addUserReport']);

    Route::delete('user/deleteUserSubscribe/{id}', [UserApiController::class, 'deleteUserSubscribe']);


    Route::get('user/getSubscribes', [UserApiController::class, 'getSubscribes']);
    Route::get('user/getConfirmedReports', [UserApiController::class, 'getConfirmedReports']);
    Route::get('user/getFiresNearMe', [UserApiController::class, 'getFiresNearMe']);


});




////
