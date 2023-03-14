<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;

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

Route::get("/articles", [ArticleController::class, "index"]);

Route::get("/articles/{id}", [ArticleController::class, "show"]);

Route::post("/articles", [ArticleController::class, "store"]);

Route::post("/articles/{id}", [ArticleController::class, "update"]);

Route::delete("/articles/{id}", [ArticleController::class, "destroy"]);





Route::get("/categories", [CategoryController::class, "index"]);

Route::get("/categories/{id}", [CategoryController::class, "show"]);

Route::post("/categories", [CategoryController::class, "store"]);

Route::put("/categories/{id}", [CategoryController::class, "update"]);

Route::delete("/categories/{id}", [CategoryController::class, "destroy"]);


