<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('books', BookController::class);

Route::post('borrow', [BorrowController::class, 'store']);
Route::get('borrowed/{id}', [BorrowController::class, 'getBooksByUserId']);
Route::post('returnBooks', [BorrowController::class, 'returnBooks']);
Route::post('reserve', [BorrowController::class,'reservation']);
Route::delete('delete/reservation', [BorrowController::class,'deleteReservation']);

Route::post('create', [UserController::class,'store']);