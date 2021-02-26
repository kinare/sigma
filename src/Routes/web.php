<?php

use Illuminate\Support\Facades\Route;

Route::get('/sigma', [KTL\Sigma\Http\Controllers\SigmaController::class, 'index'])
    ->name('sigma');

Route::post('/sigma', [KTL\Sigma\Http\Controllers\SigmaController::class, 'refresh'])
    ->name('refresh');
