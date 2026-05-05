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
  <router-view></router-view>

  <nav
    class="fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-slate-200"
  >
    <div class="flex items-center justify-around py-3">
      <router-link :to="{ name: 'StudentIndex' }" v-slot="{ isActive }">
        <div
          :class="[
            'flex flex-col items-center transition-all duration-300',
            isActive ? 'text-primary-400 scale-110' : 'text-slate-600 hover:text-slate-600',
          ]"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            />
          </svg>
          <span class="text-xs mt-1 font-medium">Beranda</span>
        </div>
      </router-link>

      <router-link :to="{ name: 'StudentBorrow' }" v-slot="{ isActive }">
        <div
          :class="[
            'flex flex-col items-center transition-all duration-300 relative',
            isActive ? 'text-primary-400 scale-110' : 'text-slate-600 hover:text-slate-600',
          ]"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
            />
          </svg>
          <span class="text-xs mt-1 font-medium">Pinjamanku</span>
        </div>
      </router-link>

      <router-link :to="{ name: 'StudentChatbot' }" v-slot="{ isActive }">
        <div
          :class="[
            'flex flex-col items-center transition-all duration-300 relative',
            isActive ? 'text-primary-500 scale-110' : 'text-slate-600 hover:text-slate-600',
          ]"
        >
          <div class="relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="absolute -top-1 -right-1 flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
            </span>
          </div>
          <span class="text-xs mt-1 font-medium text-primary-500 font-bold">AI Chat</span>
        </div>
      </router-link>

      <router-link :to="{ name: 'StudentProfile' }" v-slot="{ isActive }">
        <div
          :class="[
            'flex flex-col items-center transition-all duration-300',
            isActive ? 'text-primary-400 scale-110' : 'text-slate-600 hover:text-slate-600',
          ]"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
            />
          </svg>
          <span class="text-xs mt-1 font-medium">Profil</span>
        </div>
      </router-link>
    </div>
  </nav>
</template>
