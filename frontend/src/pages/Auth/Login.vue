<script setup>
import api from '@/lib/axios'
import router from '@/router'
import { ref } from 'vue'

const formLogin = ref({
  email: '',
  password: '',
})
const isLoading = ref(false)
const errorMessages = ref([])

const token = localStorage.getItem('token')
const role = localStorage.getItem('role')

if (token) {
  if (role === 'student') {
    router.push({ name: 'StudentLayout' })
  } else if (role === 'admin') {
    router.push({ name: 'AdminLayout' })
  }
}

const handleLogin = async () => {
  try {
    isLoading.value = true
    const ress = await api.post('/auth/login', formLogin.value)
    console.log(ress)
    localStorage.setItem('token', ress.data.data.token)
    localStorage.setItem('role', ress.data.data.user.role)
    localStorage.setItem('name', ress.data.data.user.name)
    if (ress.data.data.user.role === 'admin') {
      router.push({ name: 'AdminLayout' })
    } else if (ress.data.data.user.role === 'student') {
      router.push({ name: 'StudentLayout' })
    }
    alert(ress.data.message)
  } catch (error) {
    console.log(error)
    errorMessages.value = error.response.data.errors
    alert(error.response.data.message)
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <section class="text-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <div class="inline-flex items-center gap-3 mb-4">
          <svg
            class="w-12 h-12 text-primary-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
            />
          </svg>
        </div>
        <h1 class="text-3xl font-bold">LibSmart <span class="text-gradient">AI</span></h1>
        <p class="text-slate-400 mt-2">Sistem Perpustakaan Cerdas</p>
      </div>

      <div class="bg-dark-card border border-dark-border rounded-2xl p-8 glow">
        <h2 class="text-xl font-bold mb-6 text-center">Masuk ke Akun</h2>

        <form class="space-y-5" @submit.prevent="handleLogin()">
          <div>
            <label class="block text-sm text-slate-400 mb-2">Email</label>
            <div class="relative">
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
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                />
              </svg>
              <input
                v-model="formLogin.email"
                type="email"
                placeholder="admin@sekolah.com"
                class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
              />
              <p style="color: red">{{ errorMessages?.email?.[0] }}</p>
            </div>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">Password</label>
            <div class="relative">
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
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                />
              </svg>
              <input
                v-model="formLogin.password"
                type="password"
                placeholder="••••••••"
                class="w-full pl-12 pr-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
              />
              <p style="color: red">{{ errorMessages?.email?.[0] }}</p>
            </div>
          </div>

          <button
            type="submit"
            class="w-full py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
              />
            </svg>
            {{ isLoading ? 'Loading..' : 'Masuk' }}
          </button>
        </form>

        <div class="flex items-center my-6">
          <div class="flex-1 border-t border-dark-border"></div>
          <span class="px-4 text-slate-500 text-sm">atau</span>
          <div class="flex-1 border-t border-dark-border"></div>
        </div>

        <div class="space-y-3">
          <router-link
            :to="{ name: 'RegisterStudent' }"
            class="w-full py-3 border border-dark-border rounded-xl font-medium hover:bg-dark-bg transition-colors flex items-center justify-center gap-2"
          >
            <svg
              class="w-5 h-5 text-primary-400"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
              />
            </svg>
            Daftar sebagai Siswa
          </router-link>

          <router-link
            :to="{ name: 'RegisterSchool' }"
            class="w-full py-3 border border-primary-500/50 rounded-xl font-medium text-primary-400 hover:bg-primary-500/10 transition-colors flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
              />
            </svg>
            Daftarkan Sekolah Baru
          </router-link>
        </div>
      </div>

      <p class="text-center text-slate-500 text-sm mt-8">
        © 2026 LibSmart AI - Sistem Perpustakaan Cerdas
      </p>
    </div>
  </section>
</template>
