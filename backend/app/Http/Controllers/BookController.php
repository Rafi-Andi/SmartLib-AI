<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $books = Book::when($request->search, function ($q, $search) {
            $q->where("title", 'like', '%' . $search . '%')->orWhere("author", 'like', '%' . $search . '%')->orWhere("isbn", 'like', '%' . $search . '%');
        })->when($request->category, function ($q, $category) {
            $q->where('category', $category);
        })->when($request->available_only == true, function ($q, $available_only) {
            $q->where("available_count", ">", 0);
        })->where('school_id', $user->school_id)->paginate(8);

        return response()->json([
            "message" => "Berhasil mengambil data buku",
            "data" => $books
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "title" => ['required', 'string', 'max:255'],
            "author" => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year_published' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'category' =>  ['nullable', 'string', 'max:100'],
            'stock_count' => ['required', 'integer', 'min:1']
        ]);

        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unathorized Role"
            ], 401);
        }

        $book = Book::create([
            "school_id" => $user->school->id,
            "title" => $validated['title'],
            "isbn" => $validated['isbn'],
            "publisher" => $validated['publisher'],
            "year_published" => $validated['year_published'],
            "category" => $validated['category'],
            "stock_count" => $validated['stock_count'],
            "available_count" => $validated['stock_count'],
        ]);

        return response()->json([
            "message" => "Berhasil menambahkan buku",
            "book" => $book,
        ], 200,);
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "message" => "Buku tidak ditemukan"
            ], 404);
        }
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unathorized Role"
            ], 401);
        }
        if ($book->school_id !== $user->school_id) {
            return response()->json([
                "message" => "Unathorized Role"
            ], 401);
        }

        return response()->json([
            "book" => $book
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "message" => "Buku tidak ditemukan"
            ], 404);
        }

        $user = $request->user();

        if ($user->role !== 'admin' || $book->school_id !== $user->school_id) {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }

        $data = $request->only([
            'title',
            'isbn',
            'publisher',
            'year_published',
            'category',
            'stock_count',
        ]);

        if (array_key_exists('stock_count', $data)) {
            $borrowed = $book->borrowed_count;

            if ($data['stock_count'] < $borrowed) {
                return response()->json([
                    'message' => 'Stock tidak boleh lebih kecil dari jumlah buku yang sedang dipinjam'
                ], 422);
            }

            $data['available_count'] = $data['stock_count'] - $borrowed;
        }

        $book->update($data);

        return response()->json([
            "message" => "Berhasil update buku",
            "book" => $book->fresh()
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                "message" => "Buku tidak ditemukan"
            ], 404);
        }

        $user = $request->user();

        if ($user->role !== 'admin' || $book->school_id !== $user->school_id) {
            return response()->json([
                "message" => "Unauthorized"
            ], 401);
        }

        if ($book->borrowed_count > 0) {
            return response()->json([
                "message" => "Buku sedang di pinjam"
            ], 400);
        }

        $book->delete();

        return response()->json([
            "message" => "Berhasil hapus buku"
        ], 200);
    }

    public function categories(Request $request)
    {
        $user = $request->user();

        $categories = Book::where('school_id', $user->school_id)->select('category')->distinct()->pluck('category');
        return response()->json([
            "message" => "berhasil mendapatkan kategori",
            "categories" => $categories
        ], 200);
    }
}
