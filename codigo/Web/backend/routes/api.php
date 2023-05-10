<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\NotificacionController;

Route::post('login', [UserController::class, 'loginMovil']);
Route::post('register', [UserController::class, 'registerMovil']);

Route::post('buscarProducto', [ProductoController::class, 'buscarProducto']);
Route::post('historial', [HistorialController::class, 'getHistorial']);
Route::post('producto/{id}/favorito', [HistorialController::class, 'cambiarFavorito']);
Route::get('actualizarPrecios', [PrecioController::class, 'actualizarPrecios']);
Route::post('notificaciones', [NotificacionController::class, 'notificaciones']);
