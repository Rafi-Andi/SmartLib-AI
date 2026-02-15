<script setup>
import router from '@/router'

const token = localStorage.getItem('token')
const role = localStorage.getItem('role')

if (role === 'admin') {
  router.push({ name: 'AdminLayout' })
}
if (!token) {
  router.push({ name: 'Login' })
}
</script>

<template>
  <section class="flex min-h-screen text-white bg-dark">
    <aside class="w-64 bg-dark-card border-r border-dark-border flex flex-col fixed h-full">
      <div class="p-6 border-b border-dark-border">
        <span class="text-xl font-bold text-primary-500">LibSmart</span>
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <router-link
          :to="{ name: 'AdminIndex' }"
          class="flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent transition-all text-slate-400 hover:bg-dark-hover"
          active-class="!bg-primary-500/10 !text-primary-400 !border-primary-500/30"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            />
          </svg>
          <span>Dashboard</span>
        </router-link>

        <router-link
          :to="{ name: 'AdminBooks' }"
          class="flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent transition-all text-slate-400 hover:bg-dark-hover"
          active-class="!bg-primary-500/10 !text-primary-400 !border-primary-500/30"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
            />
          </svg>
          <span>Buku</span>
        </router-link>

        <router-link
          :to="{ name: 'AdminUsers' }"
          class="flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent transition-all text-slate-400 hover:bg-dark-hover"
          active-class="!bg-primary-500/10 !text-primary-400 !border-primary-500/30"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
            />
          </svg>
          <span>Pengguna</span>
        </router-link>

        <router-link
          :to="{ name: 'AdminRfid' }"
          class="flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent transition-all text-slate-400 hover:bg-dark-hover"
          active-class="!bg-primary-500/10 !text-primary-400 !border-primary-500/30"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
            />
          </svg>
          <span>Aktivasi RFID</span>
        </router-link>
      </nav>

      <div class="p-4 border-t border-dark-border">
        <div class="flex items-center gap-3 text-sm">
          <div
            class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center font-bold"
          >
            A
          </div>
          <span class="truncate flex-1">Admin Perpus</span>
        </div>
      </div>
    </aside>

    <router-view />
  </section>
</template>
<style scoped>
body {
  background: #0a0a0f;
}

.text-gradient {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-text-fill-color: transparent;
}

::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-track {
  background: #12121a;
}
::-webkit-scrollbar-thumb {
  background: #1e1e2e;
  border-radius: 3px;
}
</style>
