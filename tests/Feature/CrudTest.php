<?php

namespace Tests\Feature;

use App\Models\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_book()
    {
        $data = [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
            'publisher' => 'Scribner',
            'quantity' => 10,
        ];

        $response = $this->postJson('/api/books', $data);

        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function it_can_get_all_books()
    {
        Book::factory()->count(5)->create();

        $response = $this->getJson('/api/books');

        $response->assertJsonCount(5);
    }

    /** @test */
    public function it_can_get_a_single_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertJson([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'publisher' => $book->publisher,
            'quantity' => $book->quantity,
        ]);
    }

    /** @test */
    public function it_can_update_a_book()
    {
        $book = Book::factory()->create();

        $data = [
            'title' => 'New Title',
            'author' => 'New Author',
            'publisher' => 'New Publisher',
            'quantity' => 5,
        ];

        $response = $this->putJson("/api/books/{$book->id}", $data);

        $this->assertDatabaseHas('books', $data);
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->id}");

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
