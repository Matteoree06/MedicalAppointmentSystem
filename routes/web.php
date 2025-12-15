<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// RUTAS PRINCIPALES DEL FRONTEND
// ==========================================
 //Route::get('/test', fn () => 'TEST OK');

// Página de inicio
Route::get('/', function () {
    return view('home');
});

// Médicos
Route::get('/medicos', function () {
    return view('medicos.index');
});

Route::get('/medicos/{id}', function ($id) {
    return view('medicos.show', compact('id'));
})->where('id', '[0-9]+');

Route::get('/citas', fn () => view('citas.index'));

Route::get('/citas/{id}', fn ($id) => view('citas.show', compact('id')))
    ->whereNumber('id');


// Especialidades (placeholder)
Route::get('/especialidades', function () {
    return view('welcome'); // Por ahora redirige a welcome
});

// Pacientes (placeholder)
Route::get('/pacientes', function () {
    return view('welcome'); // Por ahora redirige a welcome
});

