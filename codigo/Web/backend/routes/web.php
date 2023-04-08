<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'principal');
Route::view('login', 'login')->name('login')->middleware('guest');
Route::view('register', 'register')->name('register')->middleware('guest');
Route::view('favoritos', 'favoritos')->middleware('auth');
Route::view('historial', 'historial')->middleware('auth');
Route::view('notificaciones', 'notificaciones')->middleware('auth');
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
Route::post('register', [App\Http\Controllers\UserController::class, 'register']);
Route::get('/detalle/{id}', [App\Http\Controllers\ProductoController::class, 'detalle'])->name('detalle');
Route::get('/priceUpdate', [App\Http\Controllers\PrecioController::class, 'priceUpdate'])->name('priceUpdate');
Route::post('buscarProducto', [ProductoController::class, 'buscarProducto']);