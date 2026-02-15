<template>
  <router-view></router-view>
  
  <nav class="fixed bottom-0 left-0 right-0 bg-dark-card/90 backdrop-blur-lg border-t border-dark-border">
    <div class="flex items-center justify-around py-3">
      
      <router-link :to="{ name: 'StudentIndex' }" v-slot="{ isActive }">
        <div :class="[
          'flex flex-col items-center transition-all duration-300',
          isActive ? 'text-primary-400 scale-110' : 'text-slate-400 hover:text-slate-300'
        ]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="text-xs mt-1 font-medium">Beranda</span>
        </div>
      </router-link>

      <router-link :to="{ name: 'StudentBorrow' }" v-slot="{ isActive }">
        <div :class="[
          'flex flex-col items-center transition-all duration-300 relative',
          isActive ? 'text-primary-400 scale-110' : 'text-slate-400 hover:text-slate-300'
        ]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
          </svg>
          <span class="text-xs mt-1 font-medium">Pinjamanku</span>
          <span class="absolute -top-1 -right-1 w-5 h-5 bg-error-500 text-white rounded-full text-[10px] flex items-center justify-center border-2 border-dark-card">
            2
          </span>
        </div>
      </router-link>

      <router-link :to="{ name: 'StudentProfile' }" v-slot="{ isActive }">
        <div :class="[
          'flex flex-col items-center transition-all duration-300',
          isActive ? 'text-primary-400 scale-110' : 'text-slate-400 hover:text-slate-300'
        ]">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-xs mt-1 font-medium">Profil</span>
        </div>
      </router-link>

    </div>
  </nav>
</template>