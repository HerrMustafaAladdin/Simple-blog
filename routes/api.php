<?php

use App\Http\Controllers\API\V1\CategoryController;
use App\Http\Controllers\API\V1\PostController;
use App\Http\Controllers\API\V1\TagController;
use App\Http\Controllers\API\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('v1')->group(function(){
    #|--------------------------------------------------- users
    Route::apiResource('users',UserController::class);

    #|--------------------------------------------------- categories
    Route::apiResource('categories',CategoryController::class);

    #|--------------------------------------------------- tags
    Route::apiResource('tags',TagController::class);

    #|--------------------------------------------------- posts
    Route::apiResource('posts',PostController::class);
});
