<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\Api\BookRentalController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BookSearchController;

Route::get('/', function (Request $request) {
    return response()->json("Hello World!!!", 201);
});

// Register and Login
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Book Search
Route::get('book-search', [BookSearchController::class, 'search']);

Route::middleware('auth:api')->group(function () {
    Route::get('get-user', [AuthController::class, 'userInfo']);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('books', BookController::class);

    // All Permissions
    Route::get('get-permissions', [RoleController::class, 'getPermissions']);

    // Book Rental For Admin Panel
    Route::get('/books/list/rented', [BookController::class, 'listRentedBooks']);
    Route::post('/books/rent', [BookController::class, 'rentBook']);
    Route::post('/books/return', [BookController::class, 'returnBook']);

    // Book Rental
    Route::get('/frontend/books/rented', [BookRentalController::class, 'listRentedBooks']);
    Route::post('/frontend/books/rent', [BookRentalController::class, 'rentBook']);
    Route::post('/frontend/books/return', [BookRentalController::class, 'returnBook']);
});
