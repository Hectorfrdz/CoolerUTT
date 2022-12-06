<?php

use App\Http\Controllers\adafruitController;
use App\Http\Controllers\carController;
use App\Http\Controllers\feedController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\userController;
use App\Http\Controllers\verificarController;
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
Route::prefix('/v1')->group(function () {
    Route::get('/hola', function () {
        return 'Hola mundo';
    });
});

Route::post('/reg', [LoginController::class, 'register']);
Route::post('/in', [LoginController::class, 'login']);
Route::post('/out', [LoginController::class, "logout"])->middleware('auth:sanctum');
Route::put('/user/{id}', [userController::class, 'updateUser']);

Route::get('/verificarTelefono',[verificarController::class,'telefono'],function(){
})->name('verificarTelefono');
Route::post('/verificarCodigo',[verificarController::class,'codigo'],function(){
})->name('codigo');
Route::get('segundoCorreo',[verificarController::class,'segundoCorreo'],function(){
})->name('correo');

Route::post('/feed',[feedController::class,'addFeed']);
Route::put('/feed/{id}',[feedController::class,'updateFeed']);
Route::get('/feed',[feedController::class,'addFeed']);
Route::post('/car',[carController::class,'addCar']);
Route::get('/car',[carController::class,'viewCar']);
Route::put('/car/{id}',[carController::class,'updateCar']);
Route::post('/data',[adafruitController::class,'createData']);
Route::get('/data',[adafruitController::class,'seeData']);

Route::get('vista',function(){
    return view('primero',['status'=>200]);
},200);