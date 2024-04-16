<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


route::post('login',[AuthController::class, 'login']);
route::post('conf',[AuthController::class, 'conf']);
route::post('register',[AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    
    route::get('user-profile',[AuthController::class, 'userProfile']);
    route::post('logout',[AuthController::class, 'logout']);
});