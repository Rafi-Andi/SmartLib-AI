<script setup>
import api from '@/lib/axios'
import { formatDate, formatRupiah } from '@/lib/format'
import { onMounted, ref, watch } from 'vue'

const dataTransactions = ref([])
const pageActive = ref(1)
const lastPage = ref(1)
const totalItems = ref(0)
const isLoading = ref(false)
const statusActive = ref('')
const searchActive = ref('')
const dateFrom = ref('')
const dateTo = ref('')

const dataAnalytics = ref(null)

const fetchAnalytics = async () => {
  const ress = await api.get('/transactions/analytics')
  dataAnalytics.value = ress.data.data
  console.log(dataAnalytics.value)
}

const fetchTransactions = async (page = 1) => {
  isLoading.value = true
  try {
    const ress = await api.get(`transactions`, {
      params: {
        page: page,
        status: statusActive.value,
        search: searchActive.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
      },
    })

    dataTransactions.value = ress.data.data.data
    lastPage.value = ress.data.data.last_page
    totalItems.value = ress.data.data.total
    pageActive.value = page
  } catch (error) {
    console.error('Gagal mengambil data:', error)
  } finally {
    isLoading.value = false
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= lastPage.value) {
    fetchTransactions(page)
  }
}

const returnBook = async (transactionId) => {
  if (confirm('Apakah buku ini sudah dikembalikan?')) {
    alert(`Transaksi #${transactionId} dikembalikan`)
  }
}

watch(statusActive, () => {
  fetchTransactions(1)
})

let searchDebounce = null
watch(searchActive, () => {
  if (searchDebounce) clearTimeout(searchDebounce)

  searchDebounce = setTimeout(() => {
    fetchTransactions(1)
  }, 500)
})

watch([dateFrom, dateTo], () => {
  fetchTransactions(1)
})

onMounted(() => {
  fetchTransactions()
  fetchAnalytics()
})
</script>

<template>
  <main class="flex-1 ml-64 p-8">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold">Transaksi Peminjaman</h1>
        <p class="text-slate-600">Kelola peminjaman dan pengembalian buku</p>
      </div>
      <router-link
        :to="{name: 'AdminLoginKiosk'}"
        target="_blank"
        class="px-6 py-3 bg-primary-600 rounded-xl font-semibold flex items-center gap-2 text-white"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
          />
        </svg>
        Buka Mode Kiosk
      </router-link>
    </div>

    <div class="grid grid-cols-4 gap-6 mb-8">
      <div class="bg-white border border-slate-200 rounded-xl p-4">
        <p class="text-slate-600 text-sm">Total Transaksi</p>
        <p class="text-2xl font-bold mt-1">{{ dataAnalytics?.total }}</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-xl p-4">
        <p class="text-slate-600 text-sm">Sedang Dipinjam</p>
        <p class="text-2xl font-bold text-warning-500 mt-1">{{ dataAnalytics?.borrowed }}</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-xl p-4">
        <p class="text-slate-600 text-sm">Terlambat</p>
        <p class="text-2xl font-bold text-error-500 mt-1">{{ dataAnalytics?.late }}</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-xl p-4">
        <p class="text-slate-600 text-sm">Dikembalikan Hari Ini</p>
        <p class="text-2xl font-bold text-success-500 mt-1">{{ dataAnalytics?.returned_today }}</p>
      </div>
    </div>

    <div class="flex gap-4 mb-6">
      <button
        @click="statusActive = ''"
        :class="statusActive === '' ? 'bg-primary-500/10 text-primary-400' : ''"
        class="px-6 py-3 text-slate-600 border border-primary-500/30 rounded-xl font-medium"
      >
        Semua
      </button>
      <button
        @click="statusActive = 'borrowed'"
        :class="statusActive === 'borrowed' ? 'bg-primary-500/10 text-primary-400' : ''"
        class="px-6 py-3 text-slate-600 border border-primary-500/30 rounded-xl font-medium"
      >
        Dipinjam
      </button>
      <button
        @click="statusActive = 'returned'"
        :class="statusActive === 'returned' ? 'bg-primary-500/10 text-primary-400' : ''"
        class="px-6 py-3 text-slate-600 border border-primary-500/30 rounded-xl font-medium"
      >
        Dikembalikan
      </button>
    </div>

    <div class="flex gap-4 mb-6">
      <div class="flex-1 relative">
        <svg
          class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-600"
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
          v-model="searchActive"
          type="text"
          placeholder="Cari nama peminjam atau judul buku..."
          class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
      <div class="flex items-center gap-2">
        <input
          v-model="dateFrom"
          type="date"
          class="px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none text-slate-800"
        />
        <span class="text-slate-600 text-sm">s/d</span>
        <input
          v-model="dateTo"
          type="date"
          class="px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none text-slate-800"
        />
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-600 text-sm">
            <th class="px-6 py-4">ID</th>
            <th class="px-6 py-4">Peminjam</th>
            <th class="px-6 py-4">Buku</th>
            <th class="px-6 py-4">Tgl Pinjam</th>
            <th class="px-6 py-4">Jatuh Tempo</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr
            v-for="(transaction, index) in dataTransactions"
            :key="index"
            class="hover:bg-slate-100"
            :class="{
              'bg-error-500/5': transaction.status === 'borrowed' && transaction.days_late > 0,
            }"
          >
            <td class="px-6 py-4 text-slate-600">#{{ transaction.id }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-8 h-8 bg-primary-500/20 rounded-full flex items-center justify-center text-primary-400 text-sm"
                >
                  {{ transaction.user?.name?.charAt(0) }}
                </div>
                <span>{{ transaction.user?.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4">{{ transaction.book?.title }}</td>
            <td class="px-6 py-4">{{ formatDate(transaction.borrowed_at) }}</td>
            <td
              class="px-6 py-4"
              :class="{
                'text-error-500 font-medium':
                  transaction.status === 'borrowed' && transaction.days_late > 0,
              }"
            >
              {{ formatDate(transaction.due_at) }}
            </td>

            <td class="px-6 py-4">
              <span
                v-if="transaction.status === 'returned'"
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
              >
                Dikembalikan
              </span>

              <span
                v-else-if="transaction.status === 'borrowed' && transaction.days_late > 0"
                class="px-3 py-1 bg-error-500/20 text-error-500 rounded-full text-sm flex items-center gap-1 w-fit"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                  />
                </svg>
                Terlambat {{ transaction.days_late }} hari
              </span>

              <span
                v-else
                class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-sm"
              >
                Dipinjam
              </span>
            </td>

            <td class="px-6 py-4">
              <span v-if="transaction.status === 'borrowed' && transaction.days_late > 0" class="text-error-500 text-sm font-medium">
                Terlambat {{ transaction.days_late }} hari
              </span>
              <span v-else-if="transaction.status === 'borrowed'" class="text-warning-500 text-sm font-medium">
                Sedang dipinjam
              </span>
              <div v-else-if="transaction.status === 'returned' && transaction.days_late > 0">
                <span class="text-error-500 text-sm font-medium">
                  Terlambat {{ transaction.days_late }} hari (Denda: {{ (formatRupiah(transaction.fine_amount) || 0).toLocaleString('id-ID') }})
                </span>
                <p class="text-slate-600 text-xs italic mt-1">
                  Dikembalikan {{ formatDate(transaction.returned_at) }}
                </p>
              </div>
              <span v-else class="text-slate-600 text-sm italic">
                Selesai pada {{ formatDate(transaction.returned_at) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="flex items-center justify-between px-6 py-4 border-t border-slate-200">
        <p class="text-sm text-slate-600">
          Halaman {{ pageActive }} dari {{ lastPage }}
          <span v-if="totalItems">({{ totalItems }} total buku)</span>
        </p>

        <div class="flex gap-2">
          <button
            @click="changePage(pageActive - 1)"
            :disabled="pageActive === 1"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === 1 }"
            class="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors"
          >
            Sebelumnya
          </button>

          <button
            v-for="page in lastPage"
            :key="page"
            @click="changePage(page)"
            :class="[
              pageActive === page
                ? 'bg-primary-500 text-slate-800'
                : 'border border-slate-200 text-slate-600 hover:bg-slate-100',
            ]"
            class="px-4 py-2 rounded-lg transition-colors"
          >
            {{ page }}
          </button>

          <button
            @click="changePage(pageActive + 1)"
            :disabled="pageActive === lastPage"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === lastPage }"
            class="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </main>
</template>
