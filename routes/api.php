<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


route::post('login',[AuthController::class, 'login']);
route::get('conf',[AuthController::class, 'conf']);
route::post('register',[AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    
    route::get('user-profile',[AuthController::class, 'userProfile']);
    route::delete('destroy-user/{id}',[AuthController::class, 'destroyUser']);
    route::put('user-update/{id}',[AuthController::class, 'updateUser']);
    route::patch('user-update-partial/{id}',[AuthController::class, 'updatePartial']);
    route::get('users',[AuthController::class, 'allUsers']);
    route::post('logout',[AuthController::class, 'logout']);
    route::get('solicitudes-lista',[SolicitudesController::class, 'index']);
    route::post('solicitudes-create',[SolicitudesController::class, 'create']);
    route::get('solicitudes-busca/{id}',[SolicitudesController::class, 'show']);
    route::patch('solicitudes-update-partial/{id}',[SolicitudesController::class, 'edit']);
    route::put('solicitudes-update/{id}',[SolicitudesController::class, 'update']);
    route::delete('solicitudes-destroy/{id}',[SolicitudesController::class, 'destroy']);
});