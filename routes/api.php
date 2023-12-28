<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\movieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WatchlistController;

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

Route::get('/test/{query}', [movieController::class, 'setupSearch']);

Route::get('/movie/video/{id}', [movieController::class, 'getVideo']);

Route::get('/post/{movieId}/{body}/{userId}', [CommentController::class, 'store']);

Route::get('/delete/{commentsId}', [CommentController::class, 'deleteComment']);

Route::get("/update/{commentId}/{body}/", [CommentController::class, 'updateComment']);

Route::get("/getuser/{userId}", [CommentController::class, 'getAllForUser']);

Route::get("/getmovie/{movieId}", [CommentController::class, 'getAllForMovie']);

Route::get("/username/{userId}", [UserController::class, 'getUsername']);

Route::get('/watchlist/{movie_id}/{user_id}', [WatchlistController::class, 'addToWatchlist']);

Route::post('/removeFromWatchlist', [WatchlistController::class, 'removeFromWatchlist']);

Route::get('/getWatchlist/{user_id}/{movie_id}', [WatchlistController::class, 'getWatchlist']);

Route::get('getUserWatchlist/{user_id}', [WatchlistController::class, 'getUserWatchlist']);

Route::get('/getPosterPath/{id}', [movieController::class, 'getPosterPath']);


