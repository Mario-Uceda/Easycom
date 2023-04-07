<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'principal');
Route::view('login', 'login')->name('login')->middleware('guest');
Route::view('register', 'register')->name('register')->middleware('guest');
Route::view('dashboard', 'dashboard')->middleware('auth');
Route::post('login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('logout', [App\Http\Controllers\UserController::class, 'logout']);
Route::post('register', [App\Http\Controllers\UserController::class, 'register']);