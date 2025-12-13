<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

/*API Routes
Aquí definimos las rutas de la API para el CRUD de usuarios
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

Route::apiResource('usuarios', UsuarioController::class);
