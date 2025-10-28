<?php

use Illuminate\Support\Facades\Route;
use App\Services\GeminiService;

Route::get('/test-gemini', function (GeminiService $gemini) {
    $prompt = "Say hello in a friendly way";
    $response = $gemini->generateResponse($prompt);

    return response()->json([
        'success' => true,
        'prompt' => $prompt,
        'response' => $response
    ]);
});
