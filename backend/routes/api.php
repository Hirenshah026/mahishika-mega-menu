<?php

use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\MenuController;
use Illuminate\Support\Facades\Route;

Route::get('/menu', [MenuController::class, 'index']);
Route::post('/consultations', [ConsultationController::class, 'store'])->middleware('throttle:10,1');
