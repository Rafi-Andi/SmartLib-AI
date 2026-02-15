<template>
  <main class="flex-1 ml-64 p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold">Transaksi Peminjaman</h1>
        <p class="text-slate-400">Kelola peminjaman dan pengembalian buku</p>
      </div>
      <a
        href="login_kiosk.html"
        target="_blank"
        class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
          />
        </svg>
        Buka Mode Kiosk
      </a>
    </div>

    <div class="grid grid-cols-4 gap-6 mb-8">
      <div class="bg-dark-card border border-dark-border rounded-xl p-4">
        <p class="text-slate-400 text-sm">Total Transaksi</p>
        <p class="text-2xl font-bold mt-1">1,234</p>
      </div>
      <div class="bg-dark-card border border-dark-border rounded-xl p-4">
        <p class="text-slate-400 text-sm">Sedang Dipinjam</p>
        <p class="text-2xl font-bold text-warning-500 mt-1">89</p>
      </div>
      <div class="bg-dark-card border border-dark-border rounded-xl p-4">
        <p class="text-slate-400 text-sm">Terlambat</p>
        <p class="text-2xl font-bold text-error-500 mt-1">12</p>
      </div>
      <div class="bg-dark-card border border-dark-border rounded-xl p-4">
        <p class="text-slate-400 text-sm">Dikembalikan Hari Ini</p>
        <p class="text-2xl font-bold text-success-500 mt-1">8</p>
      </div>
    </div>

    <div class="flex gap-4 mb-6">
      <button
        class="px-6 py-3 bg-primary-500/10 text-primary-400 border border-primary-500/30 rounded-xl font-medium"
      >
        Semua
      </button>
      <button
        class="px-6 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 hover:bg-dark-hover"
      >
        Dipinjam
      </button>
      <button
        class="px-6 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 hover:bg-dark-hover"
      >
        Terlambat
      </button>
      <button
        class="px-6 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 hover:bg-dark-hover"
      >
        Dikembalikan
      </button>
    </div>

    <div class="flex gap-4 mb-6">
      <div class="flex-1 relative">
        <svg
          class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
          />
        </svg>
        <input
          type="text"
          placeholder="Cari nama peminjam atau judul buku..."
          class="w-full pl-12 pr-4 py-3 bg-dark-card border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
      <input
        type="date"
        class="px-4 py-3 bg-dark-card border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
      />
    </div>

    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-dark-bg">
          <tr class="text-left text-slate-400 text-sm">
            <th class="px-6 py-4">#</th>
            <th class="px-6 py-4">Peminjam</th>
            <th class="px-6 py-4">Buku</th>
            <th class="px-6 py-4">Tgl Pinjam</th>
            <th class="px-6 py-4">Jatuh Tempo</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-dark-border">
          <tr class="hover:bg-dark-hover">
            <td class="px-6 py-4 text-slate-400">#1001</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-8 h-8 bg-primary-500/20 rounded-full flex items-center justify-center text-primary-400 text-sm"
                >
                  B
                </div>
                <span>Budi Santoso</span>
              </div>
            </td>
            <td class="px-6 py-4">Laskar Pelangi</td>
            <td class="px-6 py-4">10 Jan 2026</td>
            <td class="px-6 py-4">17 Jan 2026</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm"
                >Dipinjam</span
              >
            </td>
            <td class="px-6 py-4">
              <button
                class="px-4 py-2 bg-success-500 rounded-lg text-sm font-medium flex items-center gap-2 hover:opacity-90"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                Kembalikan
              </button>
            </td>
          </tr>

          <tr class="hover:bg-dark-hover bg-error-500/5">
            <td class="px-6 py-4 text-slate-400">#998</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-8 h-8 bg-error-500/20 rounded-full flex items-center justify-center text-error-500 text-sm"
                >
                  A
                </div>
                <span>Ahmad Fauzi</span>
              </div>
            </td>
            <td class="px-6 py-4">Bumi Manusia</td>
            <td class="px-6 py-4">1 Jan 2026</td>
            <td class="px-6 py-4 text-error-500 font-medium">8 Jan 2026</td>
            <td class="px-6 py-4">
              <span
                class="px-3 py-1 bg-error-500/20 text-error-500 rounded-full text-sm flex items-center gap-1 w-fit"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
                Terlambat 5 hari
              </span>
            </td>
            <td class="px-6 py-4">
              <button
                class="px-4 py-2 bg-error-500 rounded-lg text-sm font-medium flex items-center gap-2 hover:opacity-90"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                Kembalikan (+Denda)
              </button>
            </td>
          </tr>

          <tr class="hover:bg-dark-hover">
            <td class="px-6 py-4 text-slate-400">#997</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-8 h-8 bg-accent-500/20 rounded-full flex items-center justify-center text-accent-400 text-sm"
                >
                  S
                </div>
                <span>Siti Aminah</span>
              </div>
            </td>
            <td class="px-6 py-4">Negeri 5 Menara</td>
            <td class="px-6 py-4">5 Jan 2026</td>
            <td class="px-6 py-4">12 Jan 2026</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Dikembalikan</span
              >
            </td>
            <td class="px-6 py-4">
              <span class="text-slate-500 text-sm">10 Jan 2026</span>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="flex items-center justify-between px-6 py-4 border-t border-dark-border">
        <p class="text-sm text-slate-400">Menampilkan 1-10 dari 1,234 transaksi</p>
        <div class="flex gap-2">
          <button class="px-4 py-2 border border-dark-border rounded-lg text-slate-400">
            Sebelumnya
          </button>
          <button class="px-4 py-2 bg-primary-500 rounded-lg">1</button>
          <button class="px-4 py-2 border border-dark-border rounded-lg text-slate-400">2</button>
          <button class="px-4 py-2 border border-dark-border rounded-lg text-slate-400">
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </main>
</template>