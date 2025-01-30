<?php

use App\Http\Controllers\UrlShorteningController;
use Illuminate\Support\Facades\Route;

Route::post('/encode', [UrlShorteningController::class, 'encodeUrl']);
Route::post('/decode', [UrlShorteningController::class, 'decodeUrl']);
