<script setup>
import api from '@/lib/axios'
import router from '@/router'
import { ref } from 'vue'

const formRegister = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  referral_code: '',
  nisn: '',
})

const isLoading = ref(false)
const errorMessages = ref([])
const handleRegister = async () => {
  try {
    isLoading.value = true
    if (formRegister.value.password_confirmation !== formRegister.value.password) {
      alert('Konfirmasi password tidak valid')
      return
    }
    const ress = await api.post('/auth/register', formRegister.value)
    formRegister.value = {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      referral_code: '',
      nisn: '',
    }
    router.push({ name: 'Login' })

    alert(ress.data.message)
  } catch (error) {
    console.log(error)
    alert(error.response.data.message)
    errorMessages.value = error.response.data.errors
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <section class="text-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
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

      <div class="bg-dark-card border border-dark-border rounded-2xl p-8">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-bold">Daftar Akun Siswa</h1>
          <p class="text-slate-400 mt-2">Isi data untuk mendaftar perpustakaan</p>
        </div>

        <form class="space-y-4" @submit.prevent="handleRegister()">
          <div>
            <label class="block text-sm text-slate-400 mb-2">Kode Referral Sekolah *</label>
            <input
              v-model="formRegister.referral_code"
              type="text"
              placeholder="Contoh: SMAN1JKT"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl uppercase tracking-widest text-center font-mono focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <p class="text-xs text-slate-500 mt-1">Minta kode referral ke petugas perpustakaan</p>
            <p style="color: red">{{ errorMessages?.referral_code?.[0] }}</p>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">Nama Lengkap *</label>
            <input
              v-model="formRegister.name"
              type="text"
              placeholder="Nama sesuai kartu pelajar"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <p style="color: red">{{ errorMessages?.name?.[0] }}</p>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">NISN</label>
            <input
              v-model="formRegister.nisn"
              type="text"
              placeholder="10 digit NISN"
              maxlength="10"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <p style="color: red">{{ errorMessages?.nisn?.[0] }}</p>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">Email *</label>
            <input
              v-model="formRegister.email"
              type="email"
              placeholder="email@siswa.com"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <p style="color: red">{{ errorMessages?.email?.[0] }}</p>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">Password *</label>
            <input
              v-model="formRegister.password"
              type="password"
              placeholder="Minimal 6 karakter"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
            <p style="color: red">{{ errorMessages?.password?.[0] }}</p>
          </div>

          <div>
            <label class="block text-sm text-slate-400 mb-2">Konfirmasi Password *</label>
            <input
              v-model="formRegister.password_confirmation"
              type="password"
              placeholder="Ulangi password"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
            />
          </div>

          <button
            type="submit"
            class="w-full py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold hover:opacity-90 transition-opacity mt-6 flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
              />
            </svg>
            {{ isLoading ? 'Loading..' : 'Daftar Sekarang' }}
          </button>
        </form>

        <p class="text-center text-sm text-slate-400 mt-6">
          Sudah punya akun?
          <router-link :to="{ name: 'Login' }" class="text-primary-400 hover:underline"
            >Masuk</router-link
          >
        </p>
      </div>
    </div>
  </section>
</template>
