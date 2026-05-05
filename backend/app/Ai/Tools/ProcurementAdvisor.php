<?php

namespace App\Ai\Tools;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;

class ProcurementAdvisor implements Tool
{
    public function description(): string
    {
        return 'Gunakan tool ini untuk menganalisis tren peminjaman buku dan memberikan rekomendasi kategori atau judul buku apa yang sebaiknya dibeli/ditambah stoknya untuk pengadaan perpustakaan bulan depan.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
        ];
    }

    public function handle($arguments): string
    {
        $admin = auth('sanctum')->user();
        
        if (!$admin || !$admin->school_id) {
            return "Galat: Autentikasi admin tidak valid.";
        }

        $schoolId = $admin->school_id;

        $popularCategories = Transaction::where('transactions.school_id', $schoolId)
            ->join('books', 'transactions.book_id', '=', 'books.id')
            ->select('books.category', DB::raw('count(transactions.id) as borrow_count'))
            ->groupBy('books.category')
            ->orderByDesc('borrow_count')
            ->limit(3)
            ->get();

        $lowStockHighDemand = Transaction::where('transactions.school_id', $schoolId)
            ->join('books', 'transactions.book_id', '=', 'books.id')
            ->where('books.stock_count', '<=', 3)
            ->select('books.title', 'books.stock_count', DB::raw('count(transactions.id) as borrow_count'))
            ->groupBy('books.id', 'books.title', 'books.stock_count')
            ->having('borrow_count', '>', 2) 
            ->orderByDesc('borrow_count')
            ->limit(3)
            ->get();

        if ($popularCategories->isEmpty()) {
            return "Data peminjaman saat ini belum cukup untuk memberikan rekomendasi pengadaan.";
        }

        $report = "📊 **Laporan Analisis Pengadaan Buku**\n\n";
        
        $report .= "Kategori Paling Diminati Siswa (Fokuskan pengadaan di kategori ini):\n";
        foreach ($popularCategories as $cat) {
            $report .= "- Kategori '{$cat->category}' (Telah dipinjam {$cat->borrow_count} kali)\n";
        }

        $report .= "\n";

        if ($lowStockHighDemand->isNotEmpty()) {
            $report .= "Buku Spesifik yang Perlu Ditambah Stoknya (Peminat tinggi tapi stok tipis):\n";
            foreach ($lowStockHighDemand as $book) {
                $report .= "- '{$book->title}' (Stok hanya {$book->stock_count}, tapi dipinjam {$book->borrow_count} kali)\n";
            }
        } else {
            $report .= "Saat ini stok buku spesifik yang populer masih dalam batas aman.";
        }

        return $report;
    }
}
