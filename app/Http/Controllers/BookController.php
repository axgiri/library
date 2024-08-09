<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Service\BookService;

// it is a resource controller
class BookController extends Controller
{
    public function index(BookService $bookService)
    {
        $books = $bookService->getAllBooks();
        return response()->json(['books' => $books]);
    }

    public function store(BookRequest $request, BookService $bookService)
    {
        $attributes = $request->validated();
        $bookService->create($attributes);

        return response()->json([
            'status' => 'success',
            'data' => $attributes
        ]);
    }

    public function show(BookService $bookService)
    {
        $bookService->getAllBooks();
        return response()->json([
            'status' => 'success',
        ]);
    }

    public function edit(string $id, array $attributes, BookRequest $request, BookService $bookService)
    {
        $attributes = $request->validated();
        $bookService->update($id, $attributes);
        return response()->json([
            'status' => 'success',
            'data' => $attributes,
        ]);
    }

    public function update()
    {
        //
    }

    public function destroy(string $id, BookService $bookService)
    {
        $bookService->delete($id);
        return response()->json([
            'status' => 'success',
        ]);
    }
}
