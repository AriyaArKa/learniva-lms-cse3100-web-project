<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Add auth middleware for API routes that need authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/current-user', function (Request $request) {
        return response()->json(Auth::user());
    });
});