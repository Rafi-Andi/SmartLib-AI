<template>
  <main class="flex-1 ml-64 p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold">Manajemen Pengguna</h1>
        <p class="text-slate-400">Kelola siswa dan admin perpustakaan</p>
      </div>
      <button
        class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
          />
        </svg>
        Tambah Pengguna
      </button>
    </div>

    <div class="flex gap-4 mb-6">
      <button
        class="px-6 py-3 bg-primary-500/10 text-primary-400 border border-primary-500/30 rounded-xl font-medium"
      >
        Semua (156)
      </button>
      <button
        class="px-6 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 hover:bg-dark-hover"
      >
        Siswa (150)
      </button>
      <button
        class="px-6 py-3 bg-dark-card border border-dark-border rounded-xl text-slate-400 hover:bg-dark-hover"
      >
        Admin (6)
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
          placeholder="Cari nama, email, atau NISN..."
          class="w-full pl-12 pr-4 py-3 bg-dark-card border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
    </div>

    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-dark-bg">
          <tr class="text-left text-slate-400 text-sm">
            <th class="px-6 py-4">Pengguna</th>
            <th class="px-6 py-4">NISN</th>
            <th class="px-6 py-4">Role</th>
            <th class="px-6 py-4">RFID</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-dark-border">
          <tr class="hover:bg-dark-hover">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center font-bold"
                >
                  B
                </div>
                <div>
                  <p class="font-medium">Budi Santoso</p>
                  <p class="text-sm text-slate-400">budi@siswa.com</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 font-mono text-sm">0012345678</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-accent-500/20 text-accent-400 rounded-full text-sm"
                >Siswa</span
              >
            </td>
            <td class="px-6 py-4">
              <span
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm flex items-center gap-1 w-fit"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                Aktif
              </span>
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Aktif</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  class="p-2 text-slate-400 hover:text-primary-400 hover:bg-dark-bg rounded-lg"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>
                <button
                  class="p-2 text-slate-400 hover:text-error-500 hover:bg-dark-bg rounded-lg"
                  title="Hapus"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-dark-hover">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 bg-gradient-to-br from-accent-500 to-primary-500 rounded-full flex items-center justify-center font-bold"
                >
                  S
                </div>
                <div>
                  <p class="font-medium">Siti Aminah</p>
                  <p class="text-sm text-slate-400">siti@siswa.com</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 font-mono text-sm">0012345679</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-accent-500/20 text-accent-400 rounded-full text-sm"
                >Siswa</span
              >
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm"
                >Belum Aktif</span
              >
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Aktif</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  class="p-2 text-slate-400 hover:text-primary-400 hover:bg-dark-bg rounded-lg"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>
                <button class="p-2 text-slate-400 hover:text-error-500 hover:bg-dark-bg rounded-lg">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>

          <tr class="hover:bg-dark-hover">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 bg-gradient-to-br from-warning-500 to-error-500 rounded-full flex items-center justify-center font-bold"
                >
                  A
                </div>
                <div>
                  <p class="font-medium">Admin Perpustakaan</p>
                  <p class="text-sm text-slate-400">admin@sekolah.com</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-slate-500">-</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm"
                >Admin</span
              >
            </td>
            <td class="px-6 py-4 text-slate-500">-</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Aktif</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  class="p-2 text-slate-400 hover:text-primary-400 hover:bg-dark-bg rounded-lg"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="flex items-center justify-between px-6 py-4 border-t border-dark-border">
        <p class="text-sm text-slate-400">Menampilkan 1-10 dari 156 pengguna</p>
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
