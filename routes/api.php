<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::prefix('users')->group(function () {
    Route::get('/index', [UserController::class, 'index'])->name('users.index'); 
    Route::post('/', [UserController::class, 'store'])->name('users.store');       
    Route::get('/show/{id}', [UserController::class, 'show'])->name('users.show');         
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');   
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.delete'); 
});
