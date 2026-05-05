<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchBooks; 
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
               '1. Saat user bertanya stok atau judul, Anda WAJIB memanggil SearchBooks dengan hanya menggunakan KATA BENDA inti (contoh: "buku Makanya mikir" -> query: "Makanya mikir"). ' .
               '2. Laporkan angka "available_count" secara persis apa adanya sesuai data dari tool. ' .
               '3. PENTING: Anda HARUS memberikan jawaban dalam bentuk teks secara langsung setelah menggunakan tool. JANGAN mencoba menggunakan tool berkali-kali.';
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

        $recentLoans = \App\Models\Transaction::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->limit(3)
            ->get();

        return $recentLoans->map(function ($trx) use ($user) {
            $status = $trx->returned_at ? 'sudah dikembalikan' : 'masih dipinjam';

            return new Message(
                MessageRole::Assistant,
                "Catatan sistem: Siswa atas nama {$user->name} ini memiliki riwayat pinjam buku '{$trx->book->title}' dengan status {$status}."
            );
        })->all();
    }
}
