<?php

namespace App\Ai\Tools;

use App\Models\Book;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;

class LibraryAnalytics implements Tool
{
    public function description(): string
    {
        return 'Gunakan tool ini untuk mendapatkan data statistik perpustakaan (buku, transaksi, siswa) khusus untuk sekolah Anda sendiri.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'type' => $schema->string()
                ->enum(['general', 'popular_books', 'categories', 'users'])
                ->description('Jenis statistik yang diminta')
                ->required(),
        ];
    }

    public function handle($arguments): string
    {
        $admin = auth('sanctum')->user(); 

        if (!$admin || !$admin->school_id) {
            return "Galat: Anda harus login sebagai admin sekolah untuk melihat statistik.";
        }

        $type = $arguments['type'] ?? 'general';

        return match ($type) {
            'popular_books' => $this->getPopularBooks($admin->school_id),
            'categories' => $this->getCategoryStats($admin->school_id),
            'users' => $this->getUserStats($admin->school_id),
            default => $this->getGeneralStats($admin->school_id),
        };
    }

    private function getGeneralStats($schoolId): string
    {
        $totalBooks = Book::where('school_id', $schoolId)->sum('stock_count');
        $availableBooks = Book::where('school_id', $schoolId)->sum('available_count');
        
        $totalTransactions = Transaction::where('school_id', $schoolId)->count();
        $activeLoans = Transaction::where('school_id', $schoolId)->whereNull('returned_at')->count();

        return "Statistik Umum Sekolah Anda: Total stok buku ada $totalBooks unit, tersedia di rak $availableBooks unit. Total peminjaman tercatat sebanyak $totalTransactions, dengan $activeLoans buku yang masih dibawa siswa.";
    }

    private function getPopularBooks($schoolId): string
    {
        $popular = Transaction::where('school_id', $schoolId)
            ->select('book_id', DB::raw('count(*) as total'))
            ->groupBy('book_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with(['book' => function($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            }])
            ->get();

        if ($popular->isEmpty()) return "Belum ada data peminjaman di sekolah Anda.";

        return "5 Buku Terpopuler di Sekolah Anda:\n" . $popular->map(fn($p) => 
            "- {$p->book->title} (Dipinjam {$p->total} kali)"
        )->implode("\n");
    }

    private function getCategoryStats($schoolId): string
    {
        $stats = Book::where('school_id', $schoolId)
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderByDesc('count')
            ->get();

        return "Koleksi Kategori Sekolah Anda:\n" . $stats->map(fn($s) => 
            "- {$s->category}: {$s->count} judul"
        )->implode("\n");
    }

    private function getUserStats($schoolId): string
    {
        $totalUsers = User::where('school_id', $schoolId)->count();
        
        $activeUsers = Transaction::where('school_id', $schoolId)
            ->distinct('user_id')
            ->count('user_id');

        return "Statistik Siswa: Ada $totalUsers siswa terdaftar di sekolah Anda, $activeUsers di antaranya sudah pernah meminjam buku.";
    }
}