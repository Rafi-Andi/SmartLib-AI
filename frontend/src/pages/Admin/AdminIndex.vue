<script setup>
import api from '@/lib/axios'
import { formatDate } from '@/lib/format'
import { onMounted, ref } from 'vue'

const dataAnalytics = ref(null)

const fetchAnalytics = async () => {
  const ress = await api.get('/school/analytics')
  dataAnalytics.value = ress.data.data
  console.log(dataAnalytics.value)
}

const dataTransactions = ref(null)
const fetchTransactions = async () => {
  const ress = await api.get('/transactions')
  dataTransactions.value = ress.data.data.data
}
onMounted(() => {
  fetchAnalytics()
  fetchTransactions()
})
</script>

<template>
  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="mb-6 lg:mb-8 pt-10 lg:pt-0">
      <h1 class="text-2xl sm:text-3xl font-bold mb-2">Dashboard</h1>
      <p class="text-slate-600">Selamat datang kembali, Admin {{ dataAnalytics?.name }} !</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
          <div class="p-2 sm:p-3 bg-primary-500/10 rounded-xl">
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6 text-primary-400"
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
        </div>
        <p class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1">{{ dataAnalytics?.books_count }}</p>
        <p class="text-slate-600 text-xs sm:text-sm">Total Buku</p>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
          <div class="p-2 sm:p-3 bg-accent-500/10 rounded-xl">
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6 text-accent-400"
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
        </div>
        <p class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1">{{ dataAnalytics?.studentsCount }}</p>
        <p class="text-slate-600 text-xs sm:text-sm">Total Siswa</p>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
          <div class="p-2 sm:p-3 bg-warning-500/10 rounded-xl">
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6 text-warning-500"
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
        <p class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1">{{ dataAnalytics?.borrowsActive }}</p>
        <p class="text-slate-600 text-xs sm:text-sm">Peminjaman Aktif</p>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
          <div class="p-2 sm:p-3 bg-error-500/10 rounded-xl">
            <svg
              class="w-5 h-5 sm:w-6 sm:h-6 text-error-500"
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
        <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-error-500 mb-1">{{ dataAnalytics?.borrowsLate }}</p>
        <p class="text-slate-600 text-xs sm:text-sm">Terlambat</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
      <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
          <h2 class="text-lg sm:text-xl font-bold">Transaksi Terbaru</h2>
          <router-link :to="{name: 'AdminTransactions'}" class="text-primary-400 text-sm hover:underline">Lihat Semua</router-link>
        </div>

        <div class="overflow-x-auto -mx-4 sm:mx-0">
          <div class="min-w-[500px] px-4 sm:px-0">
            <table class="w-full">
              <thead>
                <tr class="text-left text-slate-600 text-sm border-b border-slate-200">
                  <th class="pb-4">Siswa</th>
                  <th class="pb-4">Buku</th>
                  <th class="pb-4">Status</th>
                  <th class="pb-4">Tanggal</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                <tr v-for="(item, index) in dataTransactions" :key="index">
                  <td class="py-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-8 h-8 bg-primary-500/20 rounded-full flex items-center justify-center text-primary-400 text-sm font-medium"
                      >
                        B
                      </div>
                      <span class="text-sm sm:text-base">{{ item?.user?.name }}</span>
                    </div>
                  </td>
                  <td class="py-4 text-slate-600 text-sm sm:text-base">{{ item?.book?.title }}</td>
                  <td class="py-4">
                    <span
                      v-if="item?.status === 'borrowed' && item?.days_late === 0  "
                      class="px-2 sm:px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-xs sm:text-sm"
                      >Dipinjam</span
                    >
                    <span
                      v-if="item?.status === 'returned' && item?.days_late === 0"
                      class="px-2 sm:px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-xs sm:text-sm"
                      >Dikembalikan</span
                    >
                    <span
                      v-if="item?.days_late > 0"
                      class="px-2 sm:px-3 py-1 bg-error-500/20 text-error-500 rounded-full text-xs sm:text-sm"
                      >Terlambat</span
                    >
                  </td>
                  <td class="py-4 text-slate-600 text-xs sm:text-sm">{{ formatDate(item?.borrowed_at) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-4 sm:mb-6">Aksi Cepat</h2>

        <div class="space-y-3 sm:space-y-4">
          <button
            class="w-full flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-primary-500/10 border border-primary-500/30 rounded-xl hover:bg-primary-500/20 transition-colors text-left"
          >
            <div class="p-2 sm:p-3 bg-primary-500 rounded-lg text-white flex-shrink-0">
              <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                />
              </svg>
            </div>
            <router-link :to="{name: 'AdminBooks'}">
              <p class="font-medium text-sm sm:text-base">Tambah Buku</p>
              <p class="text-xs sm:text-sm text-slate-600">Scan atau input manual</p>
            </router-link>
          </button>

          <button
            class="w-full flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl hover:border-primary-500/50 transition-colors text-left"
          >
            <div class="p-2 sm:p-3 bg-accent-500 rounded-lg flex-shrink-0">
              <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                />
              </svg>
            </div>
            <router-link :to="{name: 'AdminRfid'}">
              <p class="font-medium text-sm sm:text-base">Aktivasi RFID</p>
              <p class="text-xs sm:text-sm text-slate-600">Daftarkan kartu siswa</p>
            </router-link>
          </button>

          <button
            class="w-full flex items-center gap-3 sm:gap-4 p-3 sm:p-4 bg-slate-50 border border-slate-200 rounded-xl hover:border-primary-500/50 transition-colors text-left"
          >
            <div class="p-2 sm:p-3 bg-success-500 rounded-lg text-white flex-shrink-0">
              <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                />
              </svg>
            </div>
            <div>
              <router-link :to="{name: 'AdminKiosk'}" target="_blank" >
                <p class="font-medium text-sm sm:text-base">Proses Peminjaman</p>
                <p class="text-xs sm:text-sm text-slate-600">Buka mode kiosk</p>
              </router-link>
            </div>
          </button>
        </div>
 

        <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-slate-50 rounded-xl">
          <p class="text-sm text-slate-600 mb-1">Sekolah</p>
          <p class="font-medium text-sm sm:text-base">{{ dataAnalytics?.name }}</p>
          <p class="text-sm text-primary-400 mt-2">{{ dataAnalytics?.referral_code }}</p>
        </div>
      </div>
    </div>
  </main>
</template>
