<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApiResourceController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::post('/category-create', [ApiResourceController::class, 'createCategory']);
    Route::post('/category/update', [ApiResourceController::class, 'updateCategory']);
    Route::get('/category-index', [ApiResourceController::class, 'categoryindex']);
    Route::get('/category-posts', [ApiResourceController::class, 'categoryPosts']);


    Route::post('/create-category-posts', [ApiResourceController::class, 'postCreate']);
    Route::get('/post-detail-index', [ApiResourceController::class, 'postDetail']);
    Route::post('/post/update', [ApiResourceController::class, 'Postupdate']);
    Route::get('/post/delete/{id}', [ApiResourceController::class, 'destroy']);
});
