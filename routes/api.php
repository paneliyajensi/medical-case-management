<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\CaseApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes (Medical Device Case Management)
|--------------------------------------------------------------------------
*/

// Public Authentication Endpoints
Route::post('/login', [ApiAuthController::class, 'login']);

// Authenticated Endpoints (Sanctum Tokens)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Case Resource Endpoints
    Route::get('/cases', [CaseApiController::class, 'index']);
    Route::post('/cases', [CaseApiController::class, 'store']);
    Route::get('/cases/{id}', [CaseApiController::class, 'show']);
    Route::put('/cases/{id}', [CaseApiController::class, 'update']);
    Route::delete('/cases/{id}', [CaseApiController::class, 'destroy']);
});
