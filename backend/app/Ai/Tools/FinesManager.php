<?php

namespace App\Ai\Tools;

use App\Models\Transaction;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\DB;
use Laravel\Ai\Contracts\Tool;

class FinesManager implements Tool
{
    public function description(): string
    {
        return 'Gunakan tool ini untuk mendapatkan laporan denda perpustakaan, daftar siswa yang belum membayar denda, atau total denda yang terkumpul.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'action' => $schema->string()
                ->enum(['report', 'unpaid_list'])
                ->description('Pilih "report" untuk ringkasan denda atau "unpaid_list" untuk daftar siswa yang menunggak denda')
                ->required(),
        ];
    }

    public function handle($arguments): string
    {
        $admin = auth('sanctum')->user();
        
        if (!$admin || !$admin->school_id) {
            return "Galat: Autentikasi admin tidak valid.";
        }

        $action = $arguments['action'] ?? 'report';
        $schoolId = $admin->school_id;

        return match ($action) {
            'unpaid_list' => $this->getUnpaidList($schoolId),
            default => $this->getReport($schoolId),
        };
    }

    private function getReport($schoolId): string
    {
        // Hitung total denda yang belum dibayar
        $unpaidTotal = Transaction::where('school_id', $schoolId)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', false)
            ->sum('fine_amount');

        // Hitung total denda yang sudah dibayar
        $paidTotal = Transaction::where('school_id', $schoolId)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', true)
            ->sum('fine_amount');

        // Jumlah siswa yang menunggak
        $unpaidUsersCount = Transaction::where('school_id', $schoolId)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', false)
            ->distinct('user_id')
            ->count('user_id');

        return "Laporan Denda:\n" .
               "- Total denda yang SUDAH dibayar (Pemasukan): Rp " . number_format($paidTotal, 0, ',', '.') . "\n" .
               "- Total denda yang BELUM dibayar (Tunggakan): Rp " . number_format($unpaidTotal, 0, ',', '.') . "\n" .
               "- Jumlah siswa yang memiliki tunggakan denda: $unpaidUsersCount siswa.";
    }

    private function getUnpaidList($schoolId): string
    {
        $unpaidTransactions = Transaction::where('school_id', $schoolId)
            ->where('fine_amount', '>', 0)
            ->where('fine_paid', false)
            ->with('user')
            ->get();

        if ($unpaidTransactions->isEmpty()) {
            return "Kabar baik! Saat ini tidak ada siswa yang menunggak denda perpustakaan.";
        }

        // Kelompokkan denda per siswa
        $usersFines = [];
        foreach ($unpaidTransactions as $trx) {
            $name = $trx->user->name ?? 'Unknown';
            if (!isset($usersFines[$name])) {
                $usersFines[$name] = 0;
            }
            $usersFines[$name] += $trx->fine_amount;
        }

        $report = "Daftar Siswa dengan Tunggakan Denda:\n";
        foreach ($usersFines as $name => $amount) {
            $report .= "- $name: Menunggak Rp " . number_format($amount, 0, ',', '.') . "\n";
        }

        return $report;
    }
}
