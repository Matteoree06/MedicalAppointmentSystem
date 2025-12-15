<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 👇 RUTAS FRONTEND (CARLOS)
Route::view('/pacientes', 'pacientes.index');
Route::view('/historial', 'historial.index');
Route::view('/pagos', 'pagos.index');