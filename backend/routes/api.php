<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SchoolManagementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register-school', [AuthController::class, 'registerSchool']);
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/schools', [SchoolManagementController::class, 'index']);
    Route::apiResource('/users', UserManagementController::class);
    Route::apiResource('/books', BookController::class);
    Route::get('/books-categories', [BookController::class, 'categories']);
    Route::get('/transactions/me', [TransactionController::class, 'me']);
    Route::apiResource('/transactions', TransactionController::class);
    Route::post('/transactions/borrow', [TransactionController::class, 'borrow']);
    Route::post('/transactions/{id}/return', [TransactionController::class, 'return']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
});