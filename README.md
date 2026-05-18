#  SmartLib AI

SmartLib AI adalah sebuah sistem manajemen perpustakaan modern dan cerdas yang memadukan teknologi web terkini dengan Kecerdasan Buatan (AI). Proyek ini dirancang untuk mempermudah operasional perpustakaan sekolah atau universitas, mempercepat proses layanan, serta memberikan pengalaman interaktif dan personal bagi siswa maupun pustakawan.

##  Tujuan Proyek
Tujuan utama dari SmartLib AI adalah mengubah perpustakaan konvensional menjadi perpustakaan digital yang proaktif:
- **Otomatisasi & Integrasi RFID:** Mengurangi tugas manual pustakawan melalui sistem *Kiosk* pelayanan cepat dan login/pemindaian menggunakan kartu RFID.
- **Kecerdasan (AI):** Memanfaatkan asisten AI untuk memberikan rekomendasi buku, menjawab pertanyaan pengguna, ekstraksi data buku otomatis, dan memberikan wawasan analitik bagi admin.
- **Kepatuhan & Transparansi:** Menerapkan sistem denda otomatis dengan validasi ketat (misal: pemblokiran peminjaman baru jika ada denda yang belum dibayar).

---

##  Fitur-fitur Utama

###  Untuk Admin / Pustakawan
1. **Admin Dashboard (Vue.js SPA):** Antarmuka panel admin mandiri untuk visualisasi data analitik perpustakaan, mengelola pengguna (Siswa/Petugas), dan memantau sekolah.
2. **Manajemen Katalog & Ekstraksi AI:** Mengelola data buku dan kategori. Dilengkapi dengan fitur **AI Extract Book** yang dapat secara otomatis mengekstrak detail buku untuk mempermudah entri data.
3. **Kiosk Pelayanan Cepat & RFID:** Antarmuka Kiosk (`AdminKiosk`) yang mendukung login RFID untuk melayani peminjaman dan pengembalian buku di lokasi secara instan, dilengkapi fitur pencetakan struk (Receipt Printing) dan paginasi katalog.
4. **Manajemen Transaksi & Denda:** Melacak riwayat peminjaman, memvalidasi pengembalian, dan memproses pembayaran denda keterlambatan (Fine Dashboard).
5. **Admin AI Chatbot:** Asisten AI khusus untuk membantu admin menganalisis data perpustakaan atau mendapatkan informasi operasional secara cepat.

###  Untuk Siswa / Pengunjung
1. **Student Dashboard & Portal:** Antarmuka responsif bagi siswa untuk melihat katalog buku, memeriksa status pinjaman aktif, serta tanggungan denda mereka.
2. **Student AI Chatbot:** Asisten interaktif 24/7 yang membantu siswa mencari rekomendasi buku secara spesifik, mengecek ketersediaan katalog, dan menjawab pertanyaan seputar perpustakaan (mendukung format *Markdown* untuk balasan rapi).

###  Fitur Keamanan & Validasi
- **Unpaid Fine Restrictions:** Sistem secara otomatis memblokir siswa yang mencoba meminjam buku baru apabila mereka masih memiliki tunggakan denda yang belum dilunasi.
- **Keamanan Konfigurasi:** Pemisahan *environment variable* yang aman (file `.env` dikecualikan dari pelacakan repositori Git).

---

##  Teknologi yang Digunakan

**Frontend (Single Page Application):**
- **Vue.js 3 & Vite:** Kerangka kerja utama antarmuka pengguna yang cepat dan responsif.
- **Vue Router & Pinia:** Pengelolaan *routing* halaman dan *state management*.
- **TailwindCSS:** *Styling* antarmuka berbasis utilitas yang modern dan *clean*.
- **Chart.js (vue-chartjs):** Visualisasi grafik analitik peminjaman.
- **SweetAlert2 & Vue Sonner:** Notifikasi pop-up dan peringatan interaktif.

**Backend (REST API):**
- **Laravel 12:** Kerangka kerja utama *backend* untuk API, keamanan, dan logika bisnis.
- **Laravel Sanctum:** Autentikasi token-based yang ringan untuk aplikasi SPA.
- **MySQL:** Penyimpanan data utama (sekolah, pengguna, buku, transaksi, denda).
- **Integrasi LLM/AI (laravel/ai):** Digunakan untuk fitur *Student Agent*, *Admin Agent*, dan ekstraksi data buku pintar.

---

##  Cara Instalasi & Menjalankan Proyek Secara Lokal

### Prasyarat (Requirements)
- PHP (v8.2 atau lebih baru)
- Composer
- Node.js (v20+ disarankan) & npm/yarn
- Database MySQL

### Langkah-langkah
1. **Clone Repositori:**
   ```bash
   git clone <url-repositori-anda>
   cd "SmartLib AI"
   ```

2. **Setup Backend (Laravel REST API):**
   ```bash
   cd backend
   cp .env.example .env
   # Buka file .env dan atur koneksi DB_DATABASE, DB_USERNAME, DB_PASSWORD
   # Atur juga kredensial AI (LLM API Keys) yang dibutuhkan
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan serve
   ```
   *(Backend akan berjalan di `http://127.0.0.1:8000`)*

3. **Setup Frontend (Vue.js):**
   ```bash
   cd frontend
   cp .env.example .env
   # Buka file .env dan pastikan VITE_API_URL mengarah ke backend lokal (http://127.0.0.1:8000/api)
   npm install
   npm run dev
   ```
   *(Frontend akan berjalan di `http://localhost:5173`)*

> **Catatan Penting:** Jangan meng-commit file `.env` yang berisi rahasia kredensial (seperti API Key AI atau Password Database) ke repositori publik!

---

##  Bagaimana Cara Penggunaannya?

### 1. Penggunaan Admin / Kiosk (Di Perpustakaan Fisik)
- **Login Admin/Kiosk:** Akses web melalui `http://localhost:5173/admin` atau `/login_kiosk`. Bisa menggunakan kartu **RFID** jika perangkat mendukung.
- **Melayani Peminjaman:** Buka menu **Admin Kiosk**. Cari buku dari katalog dan tambahkan ke keranjang. Pilih nama siswa/scan kartu mereka.
- **Peringatan Denda:** Jika siswa tersebut masih memiliki denda yang belum dibayar, sistem secara otomatis akan menampilkan peringatan merah dan memblokir proses peminjaman baru sampai denda tersebut dilunasi (melalui menu **Fines**).
- **Manajemen Katalog & AI:** Jika ingin menambahkan buku baru dengan cepat, gunakan fitur *AI Extract Book* di menu **Books** untuk mempercepat input data katalog.
- **Tanya AI Admin:** Gunakan **Admin Chatbot** untuk menanyakan ringkasan aktivitas bulan ini, analitik sekolah, dsb.

### 2. Penggunaan Siswa
- **Login Siswa:** Siswa membuka portal utama dan masuk ke dalam *dashboard* mereka.
- **Berinteraksi dengan AI:** Buka menu **Chatbot**. Siswa dapat langsung meminta rekomendasi buku, misalnya: *"Saya butuh buku referensi tentang pemrograman web untuk pemula"*, dan AI akan membalas dengan katalog buku terkait yang tersedia di perpustakaan.
- **Cek Status:** Siswa dapat melihat riwayat peminjaman mereka dan apakah mereka terkena denda keterlambatan melalui menu profil atau *dashboard* mereka.

---
*Dibuat untuk memajukan literasi dan mengoptimalkan sistem manajerial perpustakaan yang pintar.* 
