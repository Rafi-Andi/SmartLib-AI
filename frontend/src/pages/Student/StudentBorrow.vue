<template>
  <div class="min-h-screen flex flex-col pb-20 text-white">
    <header
      class="sticky top-0 z-50 bg-dark-card/90 backdrop-blur-lg border-b border-dark-border p-4"
    >
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold">Pinjamanku</h1>
          <p class="text-sm text-slate-400">2 buku sedang dipinjam</p>
        </div>
        <div class="flex items-center gap-2">
          <span
            class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm font-medium"
            >2/3</span
          >
        </div>
      </div>
    </header>

    <div class="flex gap-2 p-4 bg-dark-bg">
      <button
        class="flex-1 py-3 bg-primary-500/10 text-primary-400 border border-primary-500/30 rounded-xl font-medium text-sm"
      >
        Aktif (2)
      </button>
      <button
        class="flex-1 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 text-sm"
      >
        Riwayat (15)
      </button>
    </div>

    <main class="flex-1 p-4 space-y-4">
      <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        <div class="flex gap-4 p-4">
          <div class="w-20 h-28 bg-primary-500/20 rounded-xl overflow-hidden flex-shrink-0">
            <img
              src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=150"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="flex-1">
            <h3 class="font-bold">Laskar Pelangi</h3>
            <p class="text-sm text-slate-400 mb-3">Andrea Hirata</p>

            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-400">Tanggal Pinjam</span>
                <span>10 Jan 2026</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Batas Kembali</span>
                <span class="text-warning-500 font-medium">17 Jan 2026</span>
              </div>
            </div>
          </div>
        </div>

        <div class="px-4 pb-4">
          <div class="flex items-center justify-between text-xs mb-2">
            <span class="text-slate-400">Sisa waktu</span>
            <span class="text-warning-500">4 hari lagi</span>
          </div>
          <div class="w-full bg-dark-border rounded-full h-2">
            <div class="bg-warning-500 rounded-full h-2" style="width: 60%"></div>
          </div>
        </div>
      </div>

      <div class="bg-dark-card border border-error-500/50 rounded-2xl overflow-hidden">
        <div class="bg-error-500/10 px-4 py-2 flex items-center gap-2 border-b border-error-500/30">
          <svg class="w-5 h-5 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            />
          </svg>
          <span class="text-error-500 font-medium text-sm">Terlambat 3 hari! Denda: Rp 3.000</span>
        </div>

        <div class="flex gap-4 p-4">
          <div class="w-20 h-28 bg-error-500/20 rounded-xl overflow-hidden flex-shrink-0">
            <img
              src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=150"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="flex-1">
            <h3 class="font-bold">Bumi Manusia</h3>
            <p class="text-sm text-slate-400 mb-3">Pramoedya A.T.</p>

            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span class="text-slate-400">Tanggal Pinjam</span>
                <span>1 Jan 2026</span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-400">Batas Kembali</span>
                <span class="text-error-500 font-medium line-through">8 Jan 2026</span>
              </div>
            </div>
          </div>
        </div>

        <div class="px-4 pb-4">
          <button
            class="w-full py-3 bg-error-500 rounded-xl font-medium flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 13l4 4L19 7"
              />
            </svg>
            Kembalikan Sekarang
          </button>
        </div>
      </div>

      <div class="hidden">
        <h2 class="font-bold text-lg mb-4">Riwayat Peminjaman</h2>

        <div class="bg-dark-card border border-dark-border rounded-xl p-4 flex items-center gap-4">
          <div class="w-12 h-16 bg-success-500/20 rounded-lg overflow-hidden">
            <img
              src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=100"
              class="w-full h-full object-cover"
            />
          </div>
          <div class="flex-1">
            <h3 class="font-medium">Negeri 5 Menara</h3>
            <p class="text-xs text-slate-400">Ahmad Fuadi</p>
            <p class="text-xs text-success-500 mt-1">Dikembalikan: 10 Jan 2026</p>
          </div>
          <span class="px-2 py-1 bg-success-500/20 text-success-500 rounded-full text-xs"
            >Selesai</span
          >
        </div>
      </div>
    </main>
  </div>
</template>