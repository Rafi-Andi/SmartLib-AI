<?php

namespace App\Ai\Agents;

use App\Ai\Tools\FinesManager;
use App\Ai\Tools\LibraryAnalytics;
use App\Ai\Tools\OverdueTracker;
use App\Ai\Tools\ProcurementAdvisor;
use App\Ai\Tools\SearchBooks;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;

class AdminAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): string
    {
        return 'Anda adalah Admin Analyst untuk sistem LibSmart AI. '.
               'Tugas utama Anda adalah membantu staf perpustakaan memantau performa, inventaris, keuangan, dan kepatuhan siswa. '.
               'PERATURAN KETAT: '.
               '1. ANALISIS DATA: Setiap kali Admin bertanya tentang kondisi perpustakaan, tren, atau statistik umum, Anda WAJIB menggunakan tool LibraryAnalytics. '.
               '2. AKURASI: Laporkan angka total stok, denda, dan data transaksi secara presisi. '.
               '3. DETAIL BUKU: Jika Admin menanyakan detail buku spesifik, gunakan tool SearchBooks. '.
               '4. PENGADAAN BUKU: Jika ditanya "buku apa yang harus dibeli/ditambah stoknya", Anda WAJIB memanggil tool ProcurementAdvisor. '.
               '5. DENDA (FINES): Jika ditanya total denda, laporan tunggakan, atau siapa saja yang belum bayar denda, Anda WAJIB memanggil tool FinesManager. '.
               '6. KETERLAMBATAN: Jika Admin menanyakan daftar siswa yang terlambat mengembalikan buku, Anda WAJIB menjalankan tool OverdueTracker. '.
               '7. PENTING: Anda HARUS memberikan jawaban dalam bentuk teks secara langsung setelah menggunakan tool. JANGAN mencoba menggunakan tool berkali-kali.' .
               '8. JIKA user meminta di luar konteks perpustakaan jawab dengan ramah dan sopan jika memang tidak bisa menjawab pertanyaan di luar konteks Smart LIB AI';
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new LibraryAnalytics,
            new SearchBooks,
            new OverdueTracker,
            new FinesManager,
            new ProcurementAdvisor,
        ];
    }
}
