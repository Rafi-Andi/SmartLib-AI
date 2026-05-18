<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchBooks;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools; 
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\MessageRole;
use Laravel\Ai\Promptable;

class LibrarianAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    public function instructions(): string
    {
        return 'Anda adalah Smart LIB AI SMKN 1 Surabaya. ' .
               'PERATURAN: ' .
               '1. Saat user bertanya stok atau judul, Anda WAJIB memanggil SearchBooks dengan hanya menggunakan KATA BENDA inti. ' .
               '2. JIKA user meminta REKOMENDASI BUKU secara umum, panggil SearchBooks SATU KALI saja dengan kata "rekomendasi". JANGAN menebak-nebak dan mencari judul buku acak berkali-kali. ' .
               '3. Laporkan angka "available_count" secara persis apa adanya sesuai data dari tool. ' .
               '4. PENTING: Anda HARUS memberikan jawaban dalam bentuk teks secara langsung setelah menggunakan tool SATU KALI. JANGAN pernah mencoba menggunakan tool lebih dari satu kali. ' .
               '5. JIKA user meminta di luar konteks perpustakaan jawab dengan ramah dan sopan jika memang tidak bisa menjawab pertanyaan di luar konteks Smart LIB AI. ' .
               '6. DENDA & KETERLAMBATAN: Sistem telah menyuntikkan data denda dan keterlambatan buku siswa ke dalam konteks percakapan ini. ' .
               'Jika siswa bertanya tentang denda, buku terlambat, atau status pengembalian, gunakan HANYA data dari catatan sistem tersebut — JANGAN mengarang informasi. ' .
               'Tampilkan denda dalam format Rupiah (contoh: Rp 5.000) dan sebutkan status pembayarannya (sudah/belum dibayar). ' .
               'Jika ada buku SEDANG terlambat (belum dikembalikan melewati batas), informasikan dengan jelas beserta estimasi denda yang akan dikenakan.';
    }

    public function tools(): iterable
    {
        return [
            new SearchBooks,
        ];
    }

    public function messages(): iterable
    {
        $user = auth('sanctum')->user();
        Log::info('Agent User Check:', ['user_id' => $user?->id]);

        if (! $user) {
            return [];
        }

        $messages = [];

        // --- 1. Riwayat pinjam terbaru ---
        $recentLoans = Transaction::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->limit(5)
            ->get();

        foreach ($recentLoans as $trx) {
            $status = $trx->returned_at ? 'sudah dikembalikan' : 'masih dipinjam';
            $messages[] = new Message(
                MessageRole::Assistant,
                "Catatan sistem: Siswa atas nama {$user->name} memiliki riwayat pinjam buku '{$trx->book->title}' dengan status {$status}."
            );
        }

        // --- 2. Buku yang SEDANG TERLAMBAT (masih dipinjam & melewati due date) ---
        $overdueLoans = Transaction::where('user_id', $user->id)
            ->where('status', 'borrowed')
            ->whereNull('returned_at')
            ->where('due_at', '<', now())
            ->with('book')
            ->get();

        if ($overdueLoans->isNotEmpty()) {
            foreach ($overdueLoans as $trx) {
                $daysLate = $trx->days_late;
                $messages[] = new Message(
                    MessageRole::Assistant,
                    "PERINGATAN SISTEM: Siswa {$user->name} TERLAMBAT mengembalikan buku '{$trx->book->title}'. " .
                    "Batas pengembalian: {$trx->due_at}. Sudah terlambat {$daysLate} hari. " .
                    "Estimasi denda saat ini: Rp " . number_format($daysLate * 1000, 0, ',', '.') . " (belum final, dihitung saat pengembalian)."
                );
            }
        } else {
            $messages[] = new Message(
                MessageRole::Assistant,
                "Catatan sistem: Siswa {$user->name} tidak memiliki buku yang sedang terlambat dikembalikan."
            );
        }

        // --- 3. Denda dari buku yang sudah dikembalikan (fine_amount > 0) ---
        $finedTransactions = Transaction::where('user_id', $user->id)
            ->where('fine_amount', '>', 0)
            ->with('book')
            ->latest()
            ->get();

        if ($finedTransactions->isNotEmpty()) {
            $totalUnpaid = $finedTransactions->where('fine_paid', false)->sum('fine_amount');
            $totalPaid   = $finedTransactions->where('fine_paid', true)->sum('fine_amount');

            foreach ($finedTransactions as $trx) {
                $paidStatus  = $trx->fine_paid ? 'SUDAH DIBAYAR' : 'BELUM DIBAYAR';
                $returnedAt  = $trx->returned_at ? "dikembalikan pada {$trx->returned_at}" : 'belum dikembalikan';
                $messages[] = new Message(
                    MessageRole::Assistant,
                    "Catatan denda: Buku '{$trx->book->title}' ({$returnedAt}) " .
                    "terlambat {$trx->days_late} hari — denda Rp " . number_format($trx->fine_amount, 0, ',', '.') . " [{$paidStatus}]."
                );
            }

            $messages[] = new Message(
                MessageRole::Assistant,
                "Ringkasan denda siswa {$user->name}: " .
                "Total denda BELUM DIBAYAR = Rp " . number_format($totalUnpaid, 0, ',', '.') . ". " .
                "Total denda sudah dibayar = Rp " . number_format($totalPaid, 0, ',', '.') . "."
            );
        } else {
            $messages[] = new Message(
                MessageRole::Assistant,
                "Catatan sistem: Siswa {$user->name} tidak memiliki catatan denda apapun."
            );
        }

        return $messages;
    }
}
