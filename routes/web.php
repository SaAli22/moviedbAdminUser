<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('test', ['user' => auth()->user()]);
})->middleware('auth');

Route::get('/movie', function () {
    return view('movie');
});

Route::get('/', [MovieController::class, 'loadPopular']);

Route::get('movie/{id}', [MovieController::class, 'getDetails']);

Route::post("/authuser", [UserController::class, 'authUser']);
Route::post("/signup", [UserController::class, 'createUser']);

Route::get('/logout', [UserController::class, 'logout']);
Route::get('/logoutAdmin', [UserController::class, 'logoutAdmin']);
Route::get('/createAdmin', [AdminController::class, 'createAdmin']);
Route::get('authAdmin', [AdminController::class, 'authAdmin']);
Route::get('/createAdmin', [UserController::class, 'createAdmin']);
Route::get('/getAllForMovie', [CommentController::class, 'getAllForMovie']);
Route::get('/getAllForUser', [CommentController::class, 'getAllForUser']);


Route::get('/showMovie', [MovieController::class, 'showMovie']);
Route::get('/updateDescription', [MovieController::class, 'updateDescription']);
Route::get('/movies/{id}', 'MovieController@updateDescription')->name('movies.show');
Route::get('/updateMovieDescription', [MovieController::class, 'updateMovieDescription']);
Route::put('/movies/{id}/update-description', 'MovieController@updateDescription')->name('movies.updateDescription');
Route::get('/updateDetails', [MovieController::class, 'updateDetails']);
Route::put('/api/updateMovieDetails/{movieId}', [MovieController::class, 'updateMovieDetails']);
Route::put('/updateMovieOverview/{movie_id}', [MovieController::class, 'updateMovieOverview']);




Route::post('/store', [CommentController::class, 'store']);
Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
Route::delete('/comments/{id}', [CommentController::class, 'delete'])->name('comments.delete');
Route::get('/saveMovieDescription', [MovieController::class, 'saveMovieDescription']);
