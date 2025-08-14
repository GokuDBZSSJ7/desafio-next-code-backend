<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('patients', PatientController::class)->only([
    'index','show','store','update','destroy'
]);

Route::apiResource('professionals', ProfessionalController::class)->only([
    'index','show','store','update','destroy'
]);

Route::apiResource('services', ServiceController::class)->only([
    'index','show','store','update','destroy'
]);
