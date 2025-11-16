<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;

// ------------------------------
// Rutas públicas (no requieren token)
// ------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ------------------------------
// Rutas protegidas con Sanctum
// ------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Información del usuario autenticado
    Route::get('/user', [AuthController::class, 'userData']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de citas
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

    // Usuarios (solo accesible por perfiles especiales, si se requiere)
    Route::get('/users', [UserController::class, 'index']);
});
