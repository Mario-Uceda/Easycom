<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProductoController;

Route::view('/', 'principal');
Route::view('login', 'login')->name('login')->middleware('guest');
Route::view('register', 'register')->name('register')->middleware('guest');
Route::view('favoritos', 'favoritos')->middleware('auth');
Route::view('historial', 'historial')->middleware('auth');
Route::view('notificaciones', 'notificaciones')->middleware('auth');

Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);
Route::post('register', [UserController::class, 'register']);
Route::get('/detalle/{id}', [ProductoController::class, 'detalle'])->name('detalle');
Route::post('buscarProducto', [ProductoController::class, 'buscarProductoWeb']);
Route::post('/producto/{id}/favorito', [HistorialController::class, 'cambiarFavorito']);