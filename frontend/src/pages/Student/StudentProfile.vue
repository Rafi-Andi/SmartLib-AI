<script setup>
import api from '@/lib/axios'
import { formatDate } from '@/lib/format'
import router from '@/router'
import { onMounted, ref } from 'vue'

const dataProfile = ref(null)

const fetchProfile = async () => {
  const ress = await api.get('/auth/profile')
  dataProfile.value = ress.data.data
}

const handleLogout = async () => {
  try {
    const ress = await api.post('/auth/logout')
    localStorage.removeItem('token')
    localStorage.removeItem('role')
    localStorage.removeItem('name')
    router.push({ name: 'Login' })
    alert(ress.data.message)
  } catch (error) {
    alert(error.response.data.message)
  }
}

onMounted(() => {
  fetchProfile()
})
</script>

<template>
  <div class="min-h-screen flex flex-col pb-20 text-white">
    <header class="bg-linear-to-br from-primary-500 to-accent-500 p-6 pb-16">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold">Profil Saya</h1>
      </div>
    </header>

    <div class="px-4 -mt-12">
      <div class="bg-dark-card border border-dark-border rounded-2xl p-6">
        <div class="flex items-center gap-4">
          <div
            class="w-20 h-20 bg-linear-to-br from-primary-400 to-accent-400 rounded-full flex items-center justify-center text-3xl font-bold"
          >
            {{ dataProfile?.name[0] }}
          </div>
          <div class="flex-1">
            <h2 class="text-xl font-bold">{{ dataProfile?.name }}</h2>
            <p class="text-slate-400">{{ dataProfile?.email }}</p>
            <div class="flex items-center gap-2 mt-2">
              <span class="px-2 py-1 bg-accent-500/20 text-accent-400 rounded text-xs">Siswa</span>
              <span
                v-if="dataProfile?.rfid_uid"
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
              <span
                v-if="!dataProfile?.rfid_uid"
                class="px-2 py-1 bg-red-500/20 text-red-500 rounded text-xs flex items-center gap-1"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                RFID INACTIVE
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 space-y-4">
      <div class="grid grid-cols-2 gap-4">
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-primary-400">{{ dataProfile?.totalBorrows }}</p>
          <p class="text-xs text-slate-400 mt-1">Total Dipinjam</p>
        </div>
        <div class="bg-dark-card border border-dark-border rounded-xl p-4 text-center">
          <p class="text-2xl font-bold text-warning-500">{{ dataProfile?.totalBorrowActive }}</p>
          <p class="text-xs text-slate-400 mt-1">Sedang Dipinjam</p>
        </div>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-4">
        <h3 class="font-bold mb-4">Informasi Personal</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">NISN</span>
            <span class="font-mono">{{ dataProfile?.nisn }}</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">Email</span>
            <span>{{ dataProfile?.email }}</span>
          </div>
          <div class="flex items-center justify-between py-2 border-b border-dark-border">
            <span class="text-slate-400">Sekolah</span>
            <span>{{ dataProfile?.school?.name }}</span>
          </div>
          <div class="flex items-center justify-between py-2">
            <span class="text-slate-400">Member Sejak</span>
            <span>{{ formatDate(dataProfile?.created_at) }}</span>
          </div>
        </div>
      </div>

      <div
        v-if="dataProfile?.rfid_uid"
        class="bg-linear-to-br from-primary-500/20 to-accent-500/20 border border-primary-500/30 rounded-2xl p-4"
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
              <p class="text-sm text-primary-400 font-mono">{{ dataProfile?.rfid_uid }}</p>
            </div>
          </div>
          <span class="px-3 py-1 bg-success-500 rounded-full text-sm">Aktif</span>
        </div>
      </div>
      <div
        v-if="!dataProfile?.rfid_uid"
        class="bg-linear-to-br from-red-500/20 to-red-500/20 border border-red-500/30 rounded-2xl p-4"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-red-500/30 rounded-xl flex items-center justify-center">
              <svg
                class="w-6 h-6 text-red-400"
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
              <p class="text-sm text-red-400 font-mono">-</p>
            </div>
          </div>
          <span class="px-3 py-1 bg-red-500 rounded-full text-sm">Tidak Aktif</span>
        </div>
      </div>

      <button
        @click="handleLogout()"
        class="cursor-pointer w-full py-4 border border-error-500/50 text-error-500 rounded-xl flex items-center justify-center gap-2 hover:bg-error-500/10"
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
