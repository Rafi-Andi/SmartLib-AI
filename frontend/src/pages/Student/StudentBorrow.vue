<script setup>
import api from '@/lib/axios'
import { formatDate, formatRupiah } from '@/lib/format'
import { onMounted, ref, watch } from 'vue'

const dataBorrows = ref(null)

const statusActive = ref('borrowed')

const fetchBorrows = async () => {
  const ress = await api.get('/transactions/me', {
    params: {
      status: statusActive.value,
    },
  })
  dataBorrows.value = ress.data.data
  console.log(dataBorrows.value)
  console.log(ress)
}

onMounted(() => {
  fetchBorrows()
})

watch(statusActive, () => {
  fetchBorrows()
})
const name = localStorage.getItem('name')

const defaultCover = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200'
const getBookCover = (book) => {
  return book?.cover_image_url || defaultCover
}
</script>

<template>
  <div class="min-h-screen flex flex-col pb-20 text-slate-800">
    <header
      class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-slate-200 p-4"
    >
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-bold">Pinjamanku</h1>
        </div>
        <router-link
          :to="{ name: 'StudentProfile' }"
          class="w-8 h-8 bg-primary-600 rounded-full flex items-center justify-center text-sm font-bold text-white"
        >
          {{ name[0] }}
        </router-link>
      </div>
    </header>

    <div class="flex gap-2 p-4 bg-slate-50">
      <button
        @click="statusActive = 'borrowed'"
        :class="
          statusActive === 'borrowed'
            ? 'bg-primary-500/10 text-primary-400 border border-primary-500/30'
            : 'bg-white border border-slate-200 text-slate-600'
        "
        class="flex-1 py-3 rounded-xl font-medium text-sm"
      >
        Aktif
      </button>
      <button
        @click="statusActive = 'returned'"
        :class="
          statusActive === 'returned'
            ? 'bg-primary-500/10 text-primary-400 border border-primary-500/30'
            : 'bg-white border border-slate-200 text-slate-600'
        "
        class="flex-1 py-3 rounded-xl text-sm"
      >
        Riwayat
      </button>
    </div>

    <main class="flex-1 p-4 space-y-4">
      <div v-if="statusActive === 'borrowed'">
        <h2 class="font-bold text-lg mb-4">Aktif Peminjaman</h2>

        <p class="text-center text-slate-600 py-10" v-if="dataBorrows && dataBorrows.length === 0">
          Tidak ada peminjaman aktif
        </p>

        <div
          v-for="(item, index) in dataBorrows"
          :key="index"
          :class="item?.days_late > 0 ? 'border-error-500/50' : 'border-slate-200'"
          class="bg-white border rounded-2xl overflow-hidden mb-4"
        >

          <div
            v-if="item?.days_late > 0"
            class="bg-error-500/10 px-4 py-2 flex items-center gap-2 border-b border-error-500/30"
          >
            <svg
              class="w-5 h-5 text-error-500"
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
            <span class="text-error-500 font-medium text-sm">
              Terlambat {{ item?.days_late }} hari! Denda:
              {{ formatRupiah(1000 * item?.days_late) }}
            </span>
          </div>

          <div class="flex gap-4 p-4">
            <div
              class="w-20 h-28 bg-primary-500/10 rounded-xl overflow-hidden shrink-0 border border-slate-200"
            >
              <img
                :src="getBookCover(item?.book)"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1">
              <h3 class="font-bold">{{ item?.book?.title }}</h3>
              <p class="text-sm text-slate-600 mb-3">{{ item?.book?.author }}</p>

              <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-slate-600">Tanggal Pinjam</span>
                  <span>{{ formatDate(item?.borrowed_at) }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-slate-600">Batas Kembali</span>
                  <span
                    :class="
                      item?.days_late > 0 ? 'text-error-500 line-through' : 'text-warning-500'
                    "
                    class="font-medium"
                  >
                    {{ formatDate(item?.due_at) }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <div class="px-4 pb-4" v-if="item?.days_late === 0">
            <div
              class="flex items-center justify-between text-xs p-2 bg-slate-50 rounded-lg border border-slate-200"
            >
              <span class="text-slate-600">Sisa waktu</span>
              <span class="text-warning-500 font-bold">{{ item?.days_remaining }} hari lagi</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="statusActive === 'returned'">
        <h2 class="font-bold text-lg mb-4">Riwayat Peminjaman</h2>
        <p class="text-center" v-if="!dataBorrows">Tidak ada data</p>
        <div class="flex flex-col gap-4">
          <div
            v-for="(item, index) in dataBorrows"
            :key="index"
            :class="item?.days_late > 0 ? 'border-error-500/50' : 'border-slate-200'"
            class="bg-white border rounded-xl p-4 flex items-center gap-4"
          >
            <div :class="item?.days_late > 0 ? 'bg-error-500/20' : 'bg-success-500/20'" class="w-12 h-16 rounded-lg overflow-hidden">
              <img
                :src="getBookCover(item?.book)"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="flex-1">
              <h3 class="font-medium">{{ item?.book?.title }}</h3>
              <p class="text-xs text-slate-600">{{ item?.book?.author }}</p>
              <p v-if="item?.days_late > 0" class="text-xs text-error-500 font-medium mt-1">
                Terlambat {{ item?.days_late }} hari · Denda: {{ formatRupiah(item?.fine_amount || item?.days_late * 1000) }}
              </p>
              <p class="text-xs mt-1" :class="item?.days_late > 0 ? 'text-slate-500' : 'text-success-500'">
                Dikembalikan: {{ formatDate(item?.returned_at) }}
              </p>
            </div>
            <span v-if="item?.days_late > 0" class="px-2 py-1 bg-error-500/20 text-error-500 rounded-full text-xs"
              >Terlambat</span
            >
            <span v-else class="px-2 py-1 bg-success-500/20 text-success-500 rounded-full text-xs"
              >Selesai</span
            >
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
