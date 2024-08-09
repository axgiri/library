<?php

namespace App\Http\Controllers;

use App\Http\Requests\BorrowRequest;
use App\Http\Service\BorrowService;

class BorrowController extends Controller
{
    public function store(BorrowRequest $request, BorrowService $service)
    {
        $attributes = $request->validated();
        $books = $service->borrow($attributes['user_id'], $attributes['books']);

        return response()->json([
            'status' => 'success',
            'borrowed_books' => $books['borrowed'],
            'unavailable_books' => $books['unavailable'],
        ]);
    }

    public function getBooksByUserId(BorrowService $service, $user_id)
    {
        $books = $service->getBooksByUserId($user_id);
        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function returnBooks(BorrowRequest $request, BorrowService $service){
        $attributes = $request->validated();
        $books = $service->returnBooks( $attributes['user_id'], $attributes['books']);
        return response()->json([
            'status'=> 'success',
            'returned_books' => $books['returned'],
            'not borrowed_books' => $books['nonReturnable'],
        ]);
    }

    public function reservation(BorrowRequest $request, BorrowService $service){
        $attributes = $request->validated();
        $books = $service->reservation($attributes['user_id'], $attributes['books']);

        return response()->json([
            'status'=> 'success',
            'reserved books' => $books['reserved'],
        ]);
    }

    public function deleteReservation(BorrowRequest $request, BorrowService $service){
        $attributes = $request->validated();
        $books = $service->deleteReservation($attributes['user_id'], $attributes['books']);
        return response()->json([
            'status'=> 'success',
        ]);
    }
}
