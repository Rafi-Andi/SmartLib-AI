<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $per_page = $request->per_page ?? 8;
        $books = Book::when($request->search, function ($q, $search) {
            $q->where("title", 'like', '%' . $search . '%')->orWhere("author", 'like', '%' . $search . '%')->orWhere("isbn", 'like', '%' . $search . '%');
        })->when($request->category, function ($q, $category) {
            $q->where('category', $category);
        })->when($request->available_only == true, function ($q, $available_only) {
            $q->where("available_count", ">", 0);
        })->where('school_id', $user->school_id)->latest()->paginate($per_page);

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
        $user = $request->user();
        
        $validated = $request->validate([
            "title" => ['required', 'string', 'max:255'],
            "author" => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20', Rule::unique('books')->where(function ($query) use ($user) {
            return $query->where('school_id', $user->school_id);
            })],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year_published' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'category' =>  ['nullable', 'string', 'max:100'],
            'stock_count' => ['required', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_url' => ['nullable', 'url'],
        ]);

        if ($user->role !== 'admin') {
            return response()->json([
                "message" => "Unathorized Role"
            ], 401);
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('book-covers', 'public');
        } elseif ($request->cover_url) {
            try {
                $imageContent = Http::get($request->cover_url)->body();
                $filename = 'book-covers/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, $imageContent);
                $coverPath = $filename;
            } catch (\Exception $e) {
            }
        }

        $book = Book::create([
            "school_id" => $user->school->id,
            "title" => $validated['title'],
            "author" => $validated['author'] ?? null,
            "isbn" => $validated['isbn'] ?? null,
            "publisher" => $validated['publisher'] ?? null,
            "year_published" => $validated['year_published'] ?? null,
            "category" => $validated['category'] ?? null,
            "summary" => $validated['summary'] ?? null,
            "stock_count" => $validated['stock_count'],
            "available_count" => $validated['stock_count'],
            "cover_img" => $coverPath,
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

        $data = $request->validate([
            "title" => ['nullable', 'string', 'max:255'],
            "author" => ['nullable', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $id],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year_published' => ['nullable', 'integer', 'min:1900', 'max:' . now()->year],
            'category' =>  ['nullable', 'string', 'max:100'],
            'stock_count' => ['nullable', 'integer', 'min:1'],
            'summary' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'cover_url' => ['nullable', 'url'],
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_img) {
                Storage::disk('public')->delete($book->cover_img);
            }
            $data['cover_img'] = $request->file('cover_image')->store('book-covers', 'public');
        } elseif ($request->cover_url) {
            try {
                if ($book->cover_img) {
                    Storage::disk('public')->delete($book->cover_img);
                }
                $imageContent = Http::get($request->cover_url)->body();
                $filename = 'book-covers/' . Str::random(40) . '.jpg';
                Storage::disk('public')->put($filename, $imageContent);
                $data['cover_img'] = $filename;
            } catch (\Exception $e) {
            }
        }

        unset($data['cover_image']);

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

        if ($book->cover_img) {
            Storage::disk('public')->delete($book->cover_img);
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
    public function aiExtract(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey || $apiKey === 'your_gemini_api_key_here') {
            return response()->json(['message' => 'API Key Gemini belum diatur di file .env'], 500);
        }

        $image = $request->file('image');
        $base64Image = base64_encode(file_get_contents($image->getRealPath()));

        $prompt = "Extract book metadata from this cover image. Return only a JSON object with these keys: title, author, isbn, publisher, year_published, category, summary. Use empty strings for unknown values.";

        try {
            $apiKey = trim($apiKey);
            
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->retry(3, 3000, function ($exception, $request) {
                // Retry up to 3 times with a 3-second delay if status is 429 (Too Many Requests)
                return $exception->response && $exception->response->status() === 429;
            })->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent", [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt],
                            [
                                "inline_data" => [
                                    "mime_type" => "image/jpeg",
                                    "data" => $base64Image
                                ]
                            ]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                if ($response->status() === 429) {
                    return response()->json([
                        "message" => "Batas penggunaan AI (Limit) telah tercapai atau sistem sedang sangat sibuk. Silakan isi data buku secara manual atau coba lagi nanti."
                    ], 429);
                }

                $errorData = $response->json();
                return response()->json([
                    "message" => "AI gagal diakses.",
                    "details" => [
                        "Error: " . ($errorData['error']['message'] ?? 'Unknown'),
                        "Status: " . ($errorData['error']['status'] ?? 'Unknown'),
                        "Code: " . $response->status()
                    ]
                ], 500);
            }

            $successResponse = $response->json();
            $text = $successResponse['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            $firstBrace = strpos($text, '{');
            $lastBrace = strrpos($text, '}');
            if ($firstBrace !== false && $lastBrace !== false) {
                $text = substr($text, $firstBrace, $lastBrace - $firstBrace + 1);
            }
            
            $data = json_decode($text, true);

            if (!$data || !isset($data['title'])) {
                return response()->json([
                    "message" => "AI tidak dapat mengenali informasi buku. Coba ambil foto yang lebih jelas."
                ], 422);
            }

            return response()->json([
                "message" => "Metadata berhasil diekstrak oleh AI",
                "data" => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "message" => "Terjadi kesalahan sistem: " . $e->getMessage()
            ], 500);
        }
    }
}
