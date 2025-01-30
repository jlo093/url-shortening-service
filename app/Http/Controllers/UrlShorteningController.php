<?php

namespace App\Http\Controllers;

use App\Services\UrlShorteningService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UrlShorteningController extends Controller
{
    public function __construct(
        private readonly UrlShorteningService $urlShorteningService
    ) {}

    public function encodeUrl(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $urlCode = $this->urlShorteningService->encodeUrl($request->get('url'));

        return response()->json([
            'short_url' => env('APP_URL') . '/' . $urlCode
        ]);
    }

    public function decodeUrl(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $fullUrl = $this->urlShorteningService->decodeUrl($request->get('url'));

        if ($fullUrl === null) {
            return response()->json(['error' => 'Invalid or expired URL code'], 404);
        }
        return response()->json(['original_url' => $fullUrl]);
    }
}
