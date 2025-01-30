<?php

use App\Services\UrlShorteningService;
use Illuminate\Support\Facades\Route;

Route::get('/{urlCode}', function (string $urlCode) {
    $fullUrl = (new UrlShorteningService())->decodeUrl($urlCode);

    if ($fullUrl === null) {
        return response()->json(['error' => 'Invalid or expired URL code'], 404);
    }
    return response()->redirectTo($fullUrl);
});
