<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Resources\MemberResource;

class MemberController extends Controller
{
    /**
     * Menampilkan semua member beserta buku yang sedang dipinjam.
     */
    public function index()
    {
        $members = Member::with('books')->paginate(10);
        return MemberResource::collection($members);
    }

    /**
     * Menampilkan satu member beserta buku yang sedang dipinjam.
     * (Menggunakan Route Model Binding)
     */
    public function show(Member $member)
    {
        return new MemberResource($member->load('books'));
    }

    /**
     * Member meminjam buku.
     * 1. Ambil Book berdasarkan book_id dari request
     * 2. Cek apakah buku masih tersedia (is_available = true)
     * 3. Update: set member_id & is_available = false
     * 4. Return response konfirmasi
     */
    public function borrowBook(Request $request, Member $member)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($request->book_id);

        if (!$book->is_available) {
            return response()->json([
                'message' => 'Buku sedang dipinjam',
            ], 409);
        }

        $book->update([
            'member_id'    => $member->id,
            'is_available' => false,
        ]);

        return response()->json([
            'message' => 'Buku berhasil dipinjam',
        ]);
    }

    /**
     * Member mengembalikan buku.
     * 1. Cek apakah buku memang dipinjam oleh member ini
     * 2. Update: member_id = null, is_available = true
     * 3. Return response konfirmasi
     */
    public function returnBook(Member $member, Book $book)
    {
        if ($book->member_id != $member->id) {
            return response()->json([
                'message' => 'Buku ini tidak sedang dipinjam oleh member ini',
            ], 400);
        }

        $book->update([
            'member_id'    => null,
            'is_available' => true,
        ]);

        return response()->json([
            'message' => 'Buku berhasil dikembalikan',
        ]);
    }
}
