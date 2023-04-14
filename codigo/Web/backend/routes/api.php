<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrecioController;
use App\Http\Controllers\ProductoController;

Route::post('login', [UserController::class, 'loginMovil']);
Route::post('register', [UserController::class, 'registerMovil']);

Route::post('buscarProducto', [ProductoController::class, 'buscarProducto']);
Route::post('historial', [HistorialController::class, 'getHistorial']);


Route::get('priceUpdate', [PrecioController::class, 'priceUpdate']);