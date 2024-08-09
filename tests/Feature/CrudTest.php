<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function createBookTest()
    {
        $data = Book::factory()->create()->toArray();
        $this->postJson('/api/books', $data);
        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function getAllBooksTest() {
        Book::factory()->create();
        $response = $this->get('/api/books');
        expect($response->json()['books'])->toBeArray();
    }
    


    /** @test */
    public function getByIdTest() {
        $book = Book::factory()->create();
        $response = $this->get("/api/books/{$book->id}");
        $response->assertStatus(200);
    }
    
    
    /** @test */
    public function updateBookTest()
    {
        $book = Book::factory()->create();

        $data = [
            'id'=> $book->id,
            'name'=> $book->name,
            'author' => $book->author,
            'publisher' => $book->publisher,
            'quantity' => $book->quantity,
        ];
        $this->putJson("/api/books/{$book->id}", $data);
        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function deteteBookTest()
    {
        $book = Book::factory()->create();
        $this->deleteJson("/api/books/{$book->id}");
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
