<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Middleware\CheckAge;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [TestController::class, 'index']);
Route::get('/getSize/{age}', [TestController::class, 'getSize'])->middleware(CheckAge::class);

//获取CSRFtoken
Route::get('/getToken',[TestController::class,'getToken']);

Route::group(['namespace'=>'User','prefix'=>'user'], function() {
    Route::get("/test",[UserController::class,'test']);
    Route::post("/addRandData",[UserController::class,'addRandData']);
    Route::post("/getUserRange",[UserController::class,'getUserRange']);
    Route::post("/addUser",[UserController::class,'addUser']);
    Route::post("/updateUser",[UserController::class,'updateUser']);
    Route::get("/delUser/{user_id}",[UserController::class,'delUser']);
    Route::get("/paginate/{page}",[UserController::class,'paginate']);
    Route::get("/setRedisUser/{user_id}/{name}",[UserController::class,'setRedisUser']);
    Route::get("/getRedisUser/{user_id}",[UserController::class,'getRedisUser']);
});
