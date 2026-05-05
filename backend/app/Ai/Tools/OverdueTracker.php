<?php

namespace App\Ai\Tools;

use App\Models\Transaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Tool;

class OverdueTracker implements Tool
{
    public function description(): string
    {
        return 'Gunakan tool ini untuk mencari daftar siswa yang terlambat mengembalikan buku berdasarkan jumlah hari keterlambatan.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'days_threshold' => $schema->integer()
                ->description('Batas minimal hari keterlambatan (contoh: 7 untuk telat seminggu)')
                ->default(0),
        ];
    }

    public function handle($arguments): string
    {
        $admin = auth('sanctum')->user();
        
        if (!$admin || !$admin->school_id) {
            return "Galat: Autentikasi admin tidak valid.";
        }

        $threshold = $arguments['days_threshold'] ?? 0;
        $now = Carbon::now();

        // Mencari transaksi yang belum kembali dan sudah melewati due_at
        $overdueTransactions = Transaction::where('school_id', $admin->school_id)
            ->whereNull('returned_at')
            ->where('due_at', '<', $now->subDays($threshold))
            ->with(['user', 'book'])
            ->get();

        if ($overdueTransactions->isEmpty()) {
            return "Tidak ada siswa yang terlambat mengembalikan buku untuk kriteria tersebut.";
        }

        $report = "Daftar Siswa Terlambat (Sekolah ID: {$admin->school_id}):\n";
        
        foreach ($overdueTransactions as $trx) {
            $dueDate = Carbon::parse($trx->due_at);
            $daysLate = $dueDate->diffInDays(Carbon::now());
            
            $report .= "- {$trx->user->name} | Buku: '{$trx->book->title}' | Terlambat: {$daysLate} hari (Jatuh tempo: {$dueDate->format('d M Y')})\n";
        }

        Log::info('Hasil Tool Overdue:', ['output' => $report]);

        return $report;
    }
}