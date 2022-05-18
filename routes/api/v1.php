<?php

use Illuminate\Support\Facades\Route;

Route::post('getToken', [\App\Http\Controllers\V1\Auth\AuthController::class, 'getToken']);
Route::post('spellCheck', [\App\Http\Controllers\V1\SpellCheckController::class, 'spellCheck']);
