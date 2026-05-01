<script setup>
import api from '@/lib/axios'
import router from '@/router'
import { onMounted, onUnmounted, ref } from 'vue'

const dontHaveCard = ref(false)
const currentTime = ref('')
const currentDate = ref('')

const updateDateTime = () => {
  const now = new Date()

  currentTime.value = now
    .toLocaleTimeString('id-ID', {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
    })
    .replace('.', ':')

  currentDate.value = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
}

let timer
onMounted(() => {
  updateDateTime()
  timer = setInterval(updateDateTime, 1000)
})

onUnmounted(() => {
  clearInterval(timer)
})

const inputRfid = ref('')

const studentToken = localStorage.getItem('student_token')

if (studentToken) {
  router.push({ name: 'AdminKiosk' })
}

const loginKiosk = async () => {
  try {
    const ress = await api.post('auth/kiosk', {
      rfid_uid: inputRfid.value,
    })
    alert(ress.data.message)
    localStorage.setItem('student_token', ress.data.student_token)
    router.push({ name: 'AdminKiosk' })
  } catch (error) {
    alert(error.response.data.message)
  }
}
</script>

<template>
  <div class="min-h-screen flex flex-col text-white">
    <header
      class="flex justify-between items-center p-6 bg-dark-card/50 border-b border-dark-border"
    >
      <div class="flex items-center gap-3">
        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"
          />
        </svg>
        <h1 class="text-2xl font-bold">LibSmart <span class="text-gradient">AI</span></h1>
      </div>

      <div class="text-right">
        <div id="time" class="text-4xl font-bold font-mono">{{ currentTime }} WIB</div>

        <div class="text-slate-400 text-sm">{{ currentDate }}</div>
      </div>
    </header>

    <main class="flex-1 flex items-center justify-center p-8">
      <div
        class="bg-dark-card border border-dark-border rounded-3xl p-12 max-w-lg w-full text-center glow"
      >
        <div class="relative inline-block mb-8">
          <div class="absolute inset-0 bg-primary-500/20 rounded-full rfid-wave"></div>
          <div
            class="absolute inset-0 bg-primary-500/20 rounded-full rfid-wave"
            style="animation-delay: 0.5s"
          ></div>

          <div
            class="relative z-10 w-24 h-24 bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl flex items-center justify-center pulse"
          >
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
              />
            </svg>
          </div>
        </div>

        <h2 class="text-3xl font-bold mb-4">Tempel Kartu RFID</h2>
        <p class="text-slate-400 text-lg mb-8">Tempelkan kartu perpustakaan Anda pada reader</p>

        <div class="flex items-center justify-center gap-2 text-accent-400">
          <div class="w-2 h-2 bg-accent-400 rounded-full animate-pulse"></div>
          <span>Menunggu kartu...</span>
        </div>

        <div class="flex items-center my-8">
          <div class="flex-1 border-t border-dark-border"></div>
          <span class="px-4 text-slate-500 text-sm">atau</span>
          <div class="flex-1 border-t border-dark-border"></div>
        </div>

        <button
          @click="dontHaveCard = !dontHaveCard"
          id="btnManualInput"
          class="w-full py-3 border border-dark-border rounded-xl text-slate-400 hover:bg-primary-500/10 hover:border-primary-500 hover:text-primary-400 transition-all duration-300 flex items-center justify-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"
            />
          </svg>
          Tidak punya kartu? Input RFID
        </button>

        <div id="nisnForm" v-if="dontHaveCard" class="mt-6">
          <div class="bg-dark-bg border border-primary-500/50 rounded-xl p-6">
            <div class="flex items-center gap-2 text-primary-400 mb-4">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"
                />
              </svg>
              <span class="font-medium">Input RFID</span>
            </div>

            <input
              v-model="inputRfid"
              type="text"
              placeholder="Masukkan RFID"
              class="w-full px-4 py-3 bg-dark-card border border-dark-border rounded-lg text-center text-xl tracking-widest focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
              maxlength="10"
            />

            <button
              @click="loginKiosk"
              class="w-full mt-4 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-lg font-semibold hover:opacity-90 transition-opacity flex items-center justify-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"
                />
              </svg>
              Masuk
            </button>
          </div>
        </div>
      </div>
    </main>

    <footer class="p-6 text-center text-slate-500 text-sm">
      © 2026 LibSmart AI - Sistem Perpustakaan Cerdas
    </footer>
  </div>
</template>

<style scoped>
.text-gradient {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.glow {
  box-shadow: 0 0 60px rgba(99, 102, 241, 0.3);
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

.rfid-wave {
  animation: rfid-wave 1.5s ease-in-out infinite;
}

@keyframes rfid-wave {
  0% {
    transform: scale(1);
    opacity: 0.5;
  }
  100% {
    transform: scale(2);
    opacity: 0;
  }
}
</style>
