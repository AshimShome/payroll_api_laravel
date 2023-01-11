<?php

use App\Http\Controllers\Api\AuthController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function(){
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);

    Route::middleware('auth:api')->group(function(){
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/users/{id}',[AuthController::class,'show']);
    });
   

});
