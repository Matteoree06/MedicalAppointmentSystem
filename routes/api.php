<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;   // tus rutas /usuarios
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;      // rutas del equipo /users
use App\Http\Controllers\AppointmentController;

// ------------------------------
// Ruta de prueba
// ------------------------------
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

// ------------------------------
// Tus rutas (CRUD usuarios por UsuarioController)
// OJO: esto crea /api/usuarios
// ------------------------------
Route::apiResource('usuarios', UsuarioController::class);

// ------------------------------
// Rutas públicas (no requieren token)
// ------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/validate-token', [AuthController::class, 'validateToken'])->middleware('auth:sanctum');

// ------------------------------
// Rutas protegidas con Sanctum
// ------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Información del usuario autenticado
    Route::get('/user', [AuthController::class, 'userData']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de citas (si lo van a activar luego)
    // Route::get('/appointments', [AppointmentController::class, 'index']);
    // Route::post('/appointments', [AppointmentController::class, 'store']);
    // Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    // Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    // Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

    // Usuarios del equipo (UserController) -> /api/users/...
    Route::prefix('users')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete');
    });

});
