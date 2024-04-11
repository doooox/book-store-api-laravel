<?php
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\UsersController;
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

Route::prefix('auth')->group(function () {
    Route::post('login', [UsersController::class, 'login']);
    Route::post('register', [UsersController::class, 'register']);
    Route::post('logout', [UsersController::class, 'logout']);
    Route::post('refresh', [UsersController::class, 'refresh']);
});

Route::prefix('books')->group(function () {
    Route::post("/{book}/reviews", [ReviewsController::class, 'store']);
    Route::post("add", [BooksController::class, 'store']);
    Route::get("/", [BooksController::class, 'index']);
    Route::get('/{id}', [BooksController::class, 'show']);
    Route::get('/filter/{category}', [BooksController::class, 'filteredTopRatedBooks']);
    Route::get('/category/{category}', [BooksController::class, 'getCategory']);
    Route::get('/author/{author}', [BooksController::class, 'getAuthors']);
});

Route::prefix('categories')->group(function () {
    Route::get("/", [CategoriesController::class, 'index']);
});

Route::prefix('cart')->group(function () {
    Route::get("/", [CartController::class, 'getCartItems']);
    Route::post("/add", [CartController::class, 'addToCart']);
    Route::delete("/remove", [CartController::class, 'removeFromCart']);
    Route::delete("/clear", [CartController::class, 'clearCart']);
});
