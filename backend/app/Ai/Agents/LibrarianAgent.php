<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchBooks; // Import tool yang sudah kita buat
use Illuminate\Support\Facades\Log;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools; // Kontrak wajib untuk menggunakan Tools
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\MessageRole;
use Laravel\Ai\Promptable;

class LibrarianAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Instruksi sistem: Memberi tahu AI jati dirinya.
     * Sebagai juara LKS Web, Anda bisa menambahkan instruksi yang lebih
     * spesifik di sini agar kepribadian AI lebih profesional.
     */
    public function instructions(): string
{
    return 'Anda adalah Smart LIB AI SMKN 1 Surabaya. ' .
           'PERATURAN: ' .
           '1. Saat user bertanya stok atau judul, Anda WAJIB memanggil SearchBooks dengan hanya menggunakan KATA BENDA inti (contoh: "buku Makanya mikir" -> query: "Makanya mikir"). ' .
           '2. Anda DILARANG menjawab "tidak tersedia" sebelum mencoba mencari menggunakan tool SearchBooks minimal 2 kali dengan kata kunci yang berbeda jika pencarian pertama gagal. ' .
           '3. Laporkan angka "available_count" secara persis apa adanya sesuai data dari tool.';
}

    /**
     * Mendaftarkan alat (tools) yang bisa digunakan oleh Agent.
     * Sesuai dengan dokumentasi "SalesCoach" yang Anda kirim.
     */
    public function tools(): iterable
    {
        return [
            new SearchBooks,
        ];
    }

    public function messages(): iterable
    {
        // Mengambil data user yang sedang login
        $user = auth('sanctum')->user();
        Log::info('Agent User Check:', ['user_id' => $user?->id]);

        if (! $user) {
            return [];
        }

        // Ambil 3 transaksi terakhir siswa ini
        $recentLoans = \App\Models\Transaction::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->limit(3)
            ->get();

        return $recentLoans->map(function ($trx) use ($user) {
            $status = $trx->returned_at ? 'sudah dikembalikan' : 'masih dipinjam';

            // Kita beri "ingatan" ke Gemini tentang riwayat siswa ini
            return new Message(
                MessageRole::Assistant,
                "Catatan sistem: Siswa atas nama {$user->name} ini memiliki riwayat pinjam buku '{$trx->book->title}' dengan status {$status}."
            );
        })->all();
    }
}
