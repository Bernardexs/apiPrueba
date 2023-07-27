<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\personaController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware'=>["auth:sanctum"]], function () {
    Route::get('/auth/users', [AuthController::class, 'listUser']);
    Route::delete('auth/delete/{id}', [AuthController::class, 'destroy']);
    Route::put('auth/update/{id}', [AuthController::class, 'update']);
    Route::get('auth/show/{id}', [AuthController::class, 'show']);
    Route::get('/auth/persona', [personaController::class, 'mostrar']);
    Route::put('auth/change/{id}', [personaController::class, 'cambiarPassword']);
    Route::get('auth/mostrar', [personaController::class, 'mostrarConRol']);
    Route::delete('auth/delete-user-persona/{id}', [personaController::class, 'eliminarUsuarioYPersona']);



});
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
