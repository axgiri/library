<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function borrowBookTest()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $data = [
            'user_id' => $user->id,
            'books'=> [
                [
                    'book_id' => $book->id,
                    'return_date' => now()->addMonth()->toDateString(),
                    'quantity' => 5,
                ]
            ]
        ];

        $response = $this->postJson('/api/borrow', $data);
        $response->assertStatus(200);

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed'
        ]);
    }

    /** @test */
    public function returnBookTest()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $borrowData = [
            'user_id' => $user->id,
            'books'=> [
                [
                    'book_id' => $book->id,
                    'return_date' => now()->addMonth()->toDateString(),
                    'quantity'=> 5,
                ]
            ]
        ];
        $this->postJson('/api/borrow', $borrowData);

        $returnData = [
            'user_id' => $user->id,
            'books'=> [
                [
                    'book_id' => $book->id,
                ]
            ]
        ];

        $response = $this->postJson('/api/returnBooks', $returnData);
        $response->assertStatus(200);

        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'returned',
        ]);
    }
}

