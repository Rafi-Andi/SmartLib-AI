<template>
  <main class="flex-1 ml-64 p-8">
    <div class="mb-8">
      <h1 class="text-3xl font-bold">Aktivasi Kartu RFID</h1>
      <p class="text-slate-400">Hubungkan kartu RFID dengan akun siswa</p>
    </div>

    <div class="grid grid-cols-2 gap-8">
      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
          <span
            class="w-10 h-10 bg-primary-500/20 text-primary-400 rounded-xl flex items-center justify-center font-bold"
            >1</span
          >
          <h2 class="text-lg font-bold">Pilih Siswa</h2>
        </div>

        <div class="relative mb-4">
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
            placeholder="Cari nama atau NISN siswa..."
            class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
          />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-400 mb-4">
          <input type="checkbox" checked class="w-4 h-4 rounded" />
          Hanya tampilkan yang belum punya RFID
        </label>

        <div class="space-y-2 max-h-80 overflow-y-auto">
          <button
            class="w-full flex items-center gap-4 p-4 bg-dark-bg border border-dark-border rounded-xl hover:border-primary-500 text-left transition-colors"
          >
            <div
              class="w-12 h-12 bg-gradient-to-br from-accent-500 to-primary-500 rounded-full flex items-center justify-center font-bold text-lg"
            >
              S
            </div>
            <div class="flex-1">
              <p class="font-medium">Siti Aminah</p>
              <p class="text-sm text-slate-400">NISN: 0012345679</p>
            </div>
            <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-xs"
              >Belum RFID</span
            >
          </button>

          <button
            class="w-full flex items-center gap-4 p-4 bg-dark-bg border border-dark-border rounded-xl hover:border-primary-500 text-left transition-colors"
          >
            <div
              class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center font-bold text-lg"
            >
              R
            </div>
            <div class="flex-1">
              <p class="font-medium">Rudi Hermawan</p>
              <p class="text-sm text-slate-400">NISN: 0012345680</p>
            </div>
            <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-xs"
              >Belum RFID</span
            >
          </button>

          <button
            class="w-full flex items-center gap-4 p-4 bg-dark-bg border border-dark-border rounded-xl hover:border-primary-500 text-left transition-colors"
          >
            <div
              class="w-12 h-12 bg-gradient-to-br from-success-500 to-accent-500 rounded-full flex items-center justify-center font-bold text-lg"
            >
              D
            </div>
            <div class="flex-1">
              <p class="font-medium">Dewi Lestari</p>
              <p class="text-sm text-slate-400">NISN: 0012345681</p>
            </div>
            <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-xs"
              >Belum RFID</span
            >
          </button>
        </div>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
          <span
            class="w-10 h-10 bg-accent-500/20 text-accent-400 rounded-xl flex items-center justify-center font-bold"
            >2</span
          >
          <h2 class="text-lg font-bold">Tempel Kartu RFID</h2>
        </div>

        <div class="bg-primary-500/10 border border-primary-500/30 rounded-xl p-4 mb-6">
          <p class="text-sm text-primary-400 mb-2">Siswa Terpilih</p>
          <div class="flex items-center gap-3">
            <div
              class="w-12 h-12 bg-gradient-to-br from-accent-500 to-primary-500 rounded-full flex items-center justify-center font-bold"
            >
              S
            </div>
            <div>
              <p class="font-medium">Siti Aminah</p>
              <p class="text-sm text-slate-400">NISN: 0012345679</p>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-center py-8">
          <div class="relative mb-6">
            <div
              class="w-24 h-24 bg-gradient-to-br from-accent-500 to-primary-500 rounded-2xl flex items-center justify-center pulse"
            >
              <svg
                class="w-12 h-12 text-white"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                />
              </svg>
            </div>
          </div>

          <p class="text-lg font-medium mb-2">Menunggu kartu RFID...</p>
          <p class="text-slate-400 text-center">
            Tempelkan kartu RFID pada reader untuk mengaktifkan
          </p>
        </div>

        <div class="flex items-center my-6">
          <div class="flex-1 border-t border-dark-border"></div>
          <span class="px-4 text-slate-500 text-sm">atau</span>
          <div class="flex-1 border-t border-dark-border"></div>
        </div>

        <div class="bg-dark-bg border border-dark-border rounded-xl p-4">
          <p class="text-sm text-slate-400 mb-3">Input UID Manual</p>
          <div class="flex gap-2">
            <input
              type="text"
              placeholder="Contoh: A1B2C3D4"
              class="flex-1 px-4 py-3 bg-dark-card border border-dark-border rounded-xl uppercase font-mono focus:border-primary-500 focus:outline-none"
            />
            <button class="px-4 py-3 bg-primary-500 rounded-xl font-medium hover:opacity-90">
              Terapkan
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      id="successState"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden"
    >
      <div class="bg-dark-card border border-dark-border rounded-2xl p-8 max-w-md text-center">
        <div
          class="w-20 h-20 bg-success-500/20 rounded-full flex items-center justify-center mx-auto mb-6"
        >
          <svg
            class="w-10 h-10 text-success-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            />
          </svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">RFID Berhasil Diaktivasi!</h2>
        <p class="text-slate-400 mb-6">Kartu RFID sudah terhubung dengan akun siswa</p>

        <div class="bg-dark-bg rounded-xl p-4 mb-6">
          <p class="text-sm text-slate-400">UID Kartu</p>
          <p class="text-xl font-mono font-bold text-accent-400">A1B2C3D4</p>
        </div>

        <button class="w-full py-3 bg-primary-500 rounded-xl font-semibold">
          Aktivasi Kartu Lainnya
        </button>
      </div>
    </div>
  </main>
</template>

<style scoped>
body {
  background: #0a0a0f;
}
.pulse {
  animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.8;
  }
}
</style>
