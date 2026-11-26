<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CodelabLibraryTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_borrow_book_success(): void
    {
        $member = Member::first();
        $book = Book::create([
            'title'        => 'Clean Code',
            'author'       => 'Robert C. Martin',
            'is_available' => true,
        ]);

        $response = $this->postJson("/api/members/{$member->id}/borrow-book", [
            'book_id' => $book->id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Buku berhasil dipinjam');

        $this->assertDatabaseHas('books', [
            'id'           => $book->id,
            'member_id'    => $member->id,
            'is_available' => false,
        ]);
    }

    public function test_get_member_with_borrowed_books(): void
    {
        $member = Member::first();
        $book = Book::create([
            'title'        => 'Refactoring',
            'author'       => 'Martin Fowler',
            'is_available' => false,
            'member_id'    => $member->id,
        ]);

        $response = $this->getJson("/api/members/{$member->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         'id', 'name', 'books'
                     ]
                 ]);
    }

    public function test_return_book_success(): void
    {
        $member = Member::first();
        $book = Book::create([
            'title'        => 'Design Patterns',
            'author'       => 'Gang of Four',
            'is_available' => false,
            'member_id'    => $member->id,
        ]);

        $response = $this->patchJson("/api/members/{$member->id}/return-book/{$book->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('message', 'Buku berhasil dikembalikan');

        $this->assertDatabaseHas('books', [
            'id'           => $book->id,
            'member_id'    => null,
            'is_available' => true,
        ]);
    }
}
