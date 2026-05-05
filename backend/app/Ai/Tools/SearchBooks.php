<?php

namespace App\Ai\Tools;

use App\Models\Book;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Tool;

class SearchBooks implements Tool
{
    public function description(): string
    {
        return 'Cari buku di perpustakaan berdasarkan judul atau kata kunci.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Kata kunci pencarian buku')
                ->required(),
        ];
    }

    /**
     * Handle pencarian buku.
     * 
     * Laravel AI SDK mengirim argumen sebagai Laravel\Ai\Tools\Request (ArrayAccess),
     * sehingga harus diakses dengan $arguments['key'], bukan $arguments->key.
     */
    public function handle($arguments): string
{
    Log::info('Raw Arguments dari AI:', ['data' => $arguments]);
    $user = auth('sanctum')->user();
    $query = $arguments['query'] ?? '';
    Log::info('Cleaned Query (Query Query):', ['data' => $query]);
    $books = Book::where('school_id', $user->school_id)
        ->where('title', 'like', "%{$query}%")
        ->limit(5)
        ->get();

    if ($books->isEmpty()) {
        return "Buku '$query' tidak ditemukan.";
    }

    return "Hasil pencarian: " . $books->map(fn($b) => 
        "Judul: {$b->title}, Stok Total: {$b->stock_count}, Tersedia: {$b->available_count}"
    )->implode(" | ");
}

}
