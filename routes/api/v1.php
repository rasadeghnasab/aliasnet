<?php

use Illuminate\Support\Facades\Route;

Route::post('getToken', [\App\Http\Controllers\V1\Auth\AuthController::class, 'getToken']);

