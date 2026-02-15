<template>
  <section class="text-white py-8 px-4">
    <div class="max-w-2xl mx-auto">
      <router-link
        :to="{ name: 'Login' }"
        class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18"
          />
        </svg>
        Kembali ke Login
      </router-link>

      <div class="text-center mb-8">
        <div
          class="inline-flex items-center justify-center w-16 h-16 bg-primary-500/20 rounded-2xl mb-4"
        >
          <svg
            class="w-8 h-8 text-primary-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
            />
          </svg>
        </div>
        <h1 class="text-3xl font-bold">Daftarkan Sekolah</h1>
        <p class="text-slate-400 mt-2">Mulai gunakan LibSmart AI di perpustakaan sekolah Anda</p>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-8">
        <form class="space-y-8">
          <div>
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <span
                class="w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-sm"
                >1</span
              >
              Informasi Sekolah
            </h2>

            <div class="grid gap-4">
              <div>
                <label class="block text-sm text-slate-400 mb-2">Nama Sekolah *</label>
                <input
                  type="text"
                  placeholder="Contoh: SMA Negeri 1 Jakarta"
                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                />
              </div>

              <div>
                <label class="block text-sm text-slate-400 mb-2">Alamat</label>
                <textarea
                  placeholder="Alamat lengkap sekolah"
                  rows="2"
                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none resize-none"
                ></textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-slate-400 mb-2">Telepon</label>
                  <input
                    type="tel"
                    placeholder="021-12345678"
                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                  />
                </div>
                <div>
                  <label class="block text-sm text-slate-400 mb-2">Website</label>
                  <input
                    type="url"
                    placeholder="www.sekolah.sch.id"
                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                  />
                </div>
              </div>
            </div>
          </div>

          <div>
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
              <span
                class="w-8 h-8 bg-accent-500 rounded-lg flex items-center justify-center text-sm"
                >2</span
              >
              Akun Admin
            </h2>

            <div class="grid gap-4">
              <div>
                <label class="block text-sm text-slate-400 mb-2">Nama Admin *</label>
                <input
                  type="text"
                  placeholder="Nama petugas perpustakaan"
                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                />
              </div>

              <div>
                <label class="block text-sm text-slate-400 mb-2">Email Admin *</label>
                <input
                  type="email"
                  placeholder="admin@sekolah.sch.id"
                  class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                />
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm text-slate-400 mb-2">Password *</label>
                  <input
                    type="password"
                    placeholder="Minimal 6 karakter"
                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                  />
                </div>
                <div>
                  <label class="block text-sm text-slate-400 mb-2">Konfirmasi Password *</label>
                  <input
                    type="password"
                    placeholder="Ulangi password"
                    class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
                  />
                </div>
              </div>
            </div>
          </div>

          <button
            type="submit"
            class="w-full py-4 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold text-lg hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
              />
            </svg>
            Daftarkan Sekolah
          </button>
        </form>
      </div>

      <div
        id="successState"
        class="bg-dark-card border border-dark-border rounded-2xl p-8 text-center hidden"
      >
        <div
          class="inline-flex items-center justify-center w-20 h-20 bg-success-500/20 rounded-full mb-6"
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

        <h2 class="text-2xl font-bold mb-2">Sekolah Berhasil Didaftarkan!</h2>
        <p class="text-slate-400 mb-6">Simpan kode referral berikut untuk registrasi siswa</p>

        <div class="bg-dark-bg border-2 border-dashed border-primary-500 rounded-xl p-6 mb-6">
          <p class="text-sm text-slate-400 mb-2">Kode Referral</p>
          <p class="text-4xl font-mono font-bold text-primary-400 tracking-widest">SMAN1JKT</p>
        </div>

        <div class="flex gap-4">
          <button
            class="flex-1 py-3 border border-dark-border rounded-xl hover:bg-dark-bg transition-colors flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
              />
            </svg>
            Salin Kode
          </button>
          <a
            href="login.html"
            class="flex-1 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold flex items-center justify-center gap-2"
          >
            Masuk Dashboard
          </a>
        </div>
      </div>
    </div>
  </section>
</template>
