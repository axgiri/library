<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class Borrowing extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_borrow_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $data = [
            'user_id' => $user->id,
            'books'=> [
                [
                    'book_id' => $book->id,
                    'return_date' => now()->addMonth()->toDateString(),
                ]
            ]
        ];

        $response = $this->postJson('/api/borrow', $data);
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'borrowed'
        ]);
    }

    /** @test */
    public function it_can_return_a_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $borrowData = [
            'user_id' => $user->id,
            'books'=> [
                [
                    'book_id' => $book->id,
                    'return_date' => now()->addMonth()->toDateString(),
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
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'returned',
        ]);
    }
}

