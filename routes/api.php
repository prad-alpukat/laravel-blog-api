<?php

use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

// import controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\MediaController;

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

Route::post('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/register', [AdminController::class, 'create']);

// article posts
Route::get("/{admin}/posts", [PostsController::class, "get"]);
Route::get("/{admin}/posts/{id}", [PostsController::class, "getWordPressPostById"]);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    // admin routes
    Route::get("/admin/current", [AdminController::class, 'get']);
    Route::patch("/admin/current", [AdminController::class, 'update']);
    Route::delete("/admin/logout", [AdminController::class, "logout"]);

    // article post routes with middleware
    Route::post("/{admin}/posts", [PostsController::class, "createWordPressPost"]);
    Route::patch("/{admin}/posts/{id}", [PostsController::class, "updateWordPressPost"]);
    Route::delete("/{admin}/posts/{id}", [PostsController::class, "deleteWordPressPost"]);

    // media routes
    Route::get("/{admin}/media", [MediaController::class, "get"]);
    Route::get("/{admin}/media/{id}", [MediaController::class, "getById"]);
    Route::post("/{admin}/media", [MediaController::class, "create"]);
    Route::delete("/{admin}/media/{id}", [MediaController::class, "delete"]);
});
