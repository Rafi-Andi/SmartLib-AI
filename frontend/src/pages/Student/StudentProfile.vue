<template>
  <div class="min-h-screen flex flex-col pb-20 text-white">
    <header class="bg-gradient-to-br from-primary-500 to-accent-500 p-6 pb-16">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">Profil Saya</h1>
        <button class="p-2 bg-white/20 rounded-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
        </button>
      </div>
    </header>

    <div class="px-4 -mt-12">
      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center gap-4">
          <div
            class="w-20 h-20 bg-gradient-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-3xl font-bold"
          >
            B
          </div>
          <div class="flex-1">
            <h2 class="text-xl font-bold">Budi Santoso</h2>
            <p class="text-slate-400">budi@siswa.com</p>
            <div class="flex items-center gap-2 mt-2">
              <span class="px-2 py-1 bg-accent-500/20 text-accent-400 rounded text-xs">Siswa</span>
              <span
                class="px-2 py-1 bg-success-500/20 text-success-500 rounded text-xs flex items-center gap-1"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                RFID Aktif
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 space-y-4">
      <div class="grid grid-cols-3 gap-4">
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-primary-400">15</p>
          <p class="text-xs text-slate-400 mt-1">Total Pinjaman</p>
        </div>
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-warning-500">2</p>
          <p class="text-xs text-slate-400 mt-1">Sedang Dipinjam</p>
        </div>
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-success-500">0</p>
          <p class="text-xs text-slate-400 mt-1">Denda</p>
        </div>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-4">
        <h3 class="font-bold mb-4">Informasi Personal</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">NISN</span>
            <span class="font-mono">0012345678</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">Kelas</span>
            <span>XII IPA 1</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">Sekolah</span>
            <span>SMA Negeri 1 Jakarta</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <span class="text-slate-400">Member Sejak</span>
            <span>Januari 2026</span>
          </div>
        </div>
      </div>

      <div
        class="bg-gradient-to-br from-primary-500/20 to-accent-500/20 border border-primary-500/30 rounded-2xl p-4"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-primary-500/30 rounded-xl flex items-center justify-center">
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
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                />
              </svg>
            </div>
            <div>
              <p class="font-medium">Kartu RFID</p>
              <p class="text-sm text-primary-400 font-mono">A1B2C3D4</p>
            </div>
          </div>
          <span class="px-3 py-1 bg-success-500 rounded-full text-sm">Aktif</span>
        </div>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
        <button class="w-full flex items-center justify-between p-4 hover:bg-dark-border/50">
          <div class="flex items-center gap-3">
            <svg
              class="w-5 h-5 text-slate-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
              />
            </svg>
            <span>Ubah Password</span>
          </div>
          <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </button>

        <button
          class="w-full flex items-center justify-between p-4 hover:bg-dark-border/50 border-t border-dark-border"
        >
          <div class="flex items-center gap-3">
            <svg
              class="w-5 h-5 text-slate-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
              />
            </svg>
            <span>Notifikasi</span>
          </div>
          <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </button>

        <button
          class="w-full flex items-center justify-between p-4 hover:bg-dark-border/50 border-t border-dark-border"
        >
          <div class="flex items-center gap-3">
            <svg
              class="w-5 h-5 text-slate-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span>Bantuan</span>
          </div>
          <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </button>
      </div>

      <button
        class="w-full py-4 border border-error-500/50 text-error-500 rounded-xl flex items-center justify-center gap-2 hover:bg-error-500/10"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
          />
        </svg>
        Keluar
      </button>
    </div>
  </div>
</template>
