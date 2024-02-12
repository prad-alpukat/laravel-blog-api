<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostsController;
use App\Http\Middleware\ApiAuthMiddleware;
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

Route::post('/admin/login', [AdminController::class, 'login']);

Route::get("/posts", [PostsController::class, "get"]);
Route::get("/posts/{id}", [PostsController::class, "getWordPressPostById"]);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get("/admin/current", [AdminController::class, 'get']);
    Route::patch("/admin/current", [AdminController::class, 'update']);
    Route::delete("/admin/logout", [AdminController::class, "logout"]);

    Route::post("/posts", [PostsController::class, "createWordPressPost"]);
    Route::patch("/posts/{id}", [PostsController::class, "updateWordPressPost"]);
    Route::delete("/posts/{id}", [PostsController::class, "deleteWordPressPost"]);
});
