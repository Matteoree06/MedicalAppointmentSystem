<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTAS PRINCIPALES DEL FRONTEND
// ==========================================

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

// Citas (placeholder)
Route::get('/citas', function () {
    return view('welcome'); // Por ahora redirige a welcome
});

// Especialidades
Route::get('/especialidades', function () {
    return view('especialidades.index'); 
});
Route::get('/especialidades/{id}', function ($id) {
    return view('especialidades.show', compact('id'));
})->where('id', '[0-9]+');

// Consultorios
Route::get('/consultorios', function () {
    return view('consultorios.index');
});

Route::get('/consultorios/{id}', function ($id) {
    return view('consultorios.show', compact('id'));
})->where('id', '[0-9]+');

// Pacientes (placeholder)
Route::get('/pacientes', function () {
    return view('welcome'); // Por ahora redirige a welcome
});

