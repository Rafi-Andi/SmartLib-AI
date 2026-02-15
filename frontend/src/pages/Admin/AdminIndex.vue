<template>
  <main class="flex-1 ml-64 p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold mb-2">Dashboard</h1>
      <p class="text-slate-400">Selamat datang kembali, Admin!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="p-3 bg-primary-500/10 rounded-xl">
            <svg
              class="w-6 h-6 text-primary-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
              />
            </svg>
          </div>
          <span class="text-success-500 text-sm font-medium">+12%</span>
        </div>
        <p class="text-3xl font-bold mb-1">1,234</p>
        <p class="text-slate-400 text-sm">Total Buku</p>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="p-3 bg-accent-500/10 rounded-xl">
            <svg
              class="w-6 h-6 text-accent-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
              />
            </svg>
          </div>
          <span class="text-success-500 text-sm font-medium">+5%</span>
        </div>
        <p class="text-3xl font-bold mb-1">456</p>
        <p class="text-slate-400 text-sm">Total Siswa</p>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="p-3 bg-warning-500/10 rounded-xl">
            <svg
              class="w-6 h-6 text-warning-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold mb-1">89</p>
        <p class="text-slate-400 text-sm">Peminjaman Aktif</p>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="p-3 bg-error-500/10 rounded-xl">
            <svg
              class="w-6 h-6 text-error-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
              />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-error-500 mb-1">12</p>
        <p class="text-slate-400 text-sm">Terlambat</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-bold">Transaksi Terbaru</h2>
          <a href="#" class="text-primary-400 text-sm hover:underline">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="text-left text-slate-400 text-sm border-b border-dark-border">
                <th class="pb-4">Siswa</th>
                <th class="pb-4">Buku</th>
                <th class="pb-4">Status</th>
                <th class="pb-4">Tanggal</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-dark-border">
              <tr>
                <td class="py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 bg-primary-500/20 rounded-full flex items-center justify-center text-primary-400 text-sm font-medium"
                    >
                      B
                    </div>
                    <span>Budi Santoso</span>
                  </div>
                </td>
                <td class="py-4 text-slate-300">Laskar Pelangi</td>
                <td class="py-4">
                  <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm"
                    >Dipinjam</span
                  >
                </td>
                <td class="py-4 text-slate-400 text-sm">13 Jan 2026</td>
              </tr>
              <tr>
                <td class="py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 bg-accent-500/20 rounded-full flex items-center justify-center text-accent-400 text-sm font-medium"
                    >
                      S
                    </div>
                    <span>Siti Aminah</span>
                  </div>
                </td>
                <td class="py-4 text-slate-300">Negeri 5 Menara</td>
                <td class="py-4">
                  <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                    >Dikembalikan</span
                  >
                </td>
                <td class="py-4 text-slate-400 text-sm">12 Jan 2026</td>
              </tr>
              <tr>
                <td class="py-4">
                  <div class="flex items-center gap-3">
                    <div
                      class="w-8 h-8 bg-error-500/20 rounded-full flex items-center justify-center text-error-500 text-sm font-medium"
                    >
                      A
                    </div>
                    <span>Ahmad Fauzi</span>
                  </div>
                </td>
                <td class="py-4 text-slate-300">Bumi Manusia</td>
                <td class="py-4">
                  <span class="px-3 py-1 bg-error-500/20 text-error-500 rounded-full text-sm"
                    >Terlambat</span
                  >
                </td>
                <td class="py-4 text-slate-400 text-sm">5 Jan 2026</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <h2 class="text-xl font-bold mb-6">Aksi Cepat</h2>

        <div class="space-y-4">
          <button
            class="w-full flex items-center gap-4 p-4 bg-primary-500/10 border border-primary-500/30 rounded-xl hover:bg-primary-500/20 transition-colors text-left"
          >
            <div class="p-3 bg-primary-500 rounded-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
            </div>
            <div>
              <p class="font-medium">Tambah Buku</p>
              <p class="text-sm text-slate-400">Scan atau input manual</p>
            </div>
          </button>

          <button
            class="w-full flex items-center gap-4 p-4 bg-dark-hover border border-dark-border rounded-xl hover:border-primary-500/50 transition-colors text-left"
          >
            <div class="p-3 bg-accent-500 rounded-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                />
              </svg>
            </div>
            <div>
              <p class="font-medium">Aktivasi RFID</p>
              <p class="text-sm text-slate-400">Daftarkan kartu siswa</p>
            </div>
          </button>

          <button
            class="w-full flex items-center gap-4 p-4 bg-dark-hover border border-dark-border rounded-xl hover:border-primary-500/50 transition-colors text-left"
          >
            <div class="p-3 bg-success-500 rounded-lg">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                />
              </svg>
            </div>
            <div>
              <p class="font-medium">Proses Peminjaman</p>
              <p class="text-sm text-slate-400">Buka mode kiosk</p>
            </div>
          </button>
        </div>
        
        <div class="mt-6 p-4 bg-dark-bg rounded-xl">
          <p class="text-sm text-slate-400 mb-1">Sekolah</p>
          <p class="font-medium">SMA Negeri 1 Jakarta</p>
          <p class="text-sm text-primary-400 mt-2">Kode: SMAN1JKT</p>
        </div>
      </div>
    </div>
  </main>
</template>
