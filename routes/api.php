<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\BookingController;




Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index']);
        Route::post('/', [RoomController::class, 'store']);
        Route::get('/{room}', [RoomController::class, 'show']);
        Route::put('/{room}', [RoomController::class, 'update']);
        Route::patch('/{room}', [RoomController::class, 'update']);
        Route::delete('/{room}', [RoomController::class, 'destroy']);
    });
});



Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tables', TableController::class);
});




Route::middleware(('auth-sanctum'))->group(function () {
    Route::apiResource('bookings', BookingController::class);
}); 

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
});
