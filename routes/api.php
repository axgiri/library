<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'books'], function () {
    Route::resource('/', BookController::class);
    Route::post('/return', [BorrowController::class, 'returnBooks'])->name('books.return');
    Route::post('/borrow', [BorrowController::class, 'store'])->name('books.borrow');
});

Route::get('borrowed/{id}', [BorrowController::class, 'getBooksByUserId']);
Route::post('reservation', [BorrowController::class,'reservation']);
Route::delete('reservation', [BorrowController::class,'deleteReservation']);

Route::post('createUser', [UserController::class,'store']);