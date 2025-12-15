<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\CitaController;
use Laravel\Sanctum\Sanctum;

// ------------------------------
// Rutas públicas (no requieren token)
// ------------------------------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/validate-token', [AuthController::class, 'validateToken'])->middleware('auth:sanctum');
Route::apiResource('citas', CitaController::class);

// ------------------------------
// Rutas protegidas con Sanctum
// ------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Información del usuario autenticado
    Route::get('/user', [AuthController::class, 'userData']);

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout']);

    // Usuarios (solo accesible por perfiles especiales, si se requiere)
    Route::get('/users', [UserController::class, 'index']);

    Route::prefix('users')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('users.index'); 
        Route::post('/', [UserController::class, 'store'])->name('users.store');       
        Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');         
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');   
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete'); 
    });

    // ==========================================
    // RUTAS CRUD TRADICIONALES ADICIONALES
    // ==========================================
    
    // Pacientes CRUD
    Route::apiResource('pacientes', PacienteController::class);
    
    // Médicos CRUD
    Route::apiResource('medicos', MedicoController::class);

    //Especialidad CRUD
    Route::apiResource('especialidades', EspecialidadController::class);

    //Consultorio CRUD
    Route::apiResource('consultorios', ConsultorioController::class);

    //Cita CRUD
    //Route::apiResource('citas', CitaController::class);

});

// ==========================================
// RUTAS JSON-LD (PÚBLICAS PARA DEMOSTRACIÓN)
// ==========================================

Route::prefix('jsonld')->group(function () {
    
    // Users/Pacientes JSON-LD
    Route::get('/users', [UserController::class, 'indexJsonLd']);
    Route::get('/users/{id}', [UserController::class, 'showJsonLd']);
    
    // Pacientes JSON-LD
    Route::get('/pacientes', [PacienteController::class, 'indexJsonLd']);
    Route::get('/pacientes/{id}', [PacienteController::class, 'showJsonLd']);
    Route::get('/pacientes/{id}/citas', [PacienteController::class, 'citasJsonLd']);
    
    // Médicos JSON-LD
    Route::get('/medicos', [MedicoController::class, 'indexJsonLd']);
    Route::get('/medicos/{id}', [MedicoController::class, 'showJsonLd']);
    Route::get('/medicos/{id}/citas', [MedicoController::class, 'citasJsonLd']);
    Route::get('/medicos/especialidad/{especialidadId}', [MedicoController::class, 'porEspecialidadJsonLd']);

    //Especialidad JSON-LD
    Route::get('/especialidades', [EspecialidadController::class, 'indexJsonLd']);
    Route::get('/especialidades/{id}', [EspecialidadController::class, 'showJsonLd']);

    //Consultorio JSON-LD
    Route::get('/consultorios', [ConsultorioController::class, 'indexJsonLd']);
    Route::get('/consultorios/{id}', [ConsultorioController::class, 'showJsonLd']);

    //Cita JSON-LD
    Route::get('/citas', [CitaController::class, 'indexJsonLd']);
    Route::get('/citas/{id}', [CitaController::class, 'showJsonLd']);
});