<script setup>
import api from '@/lib/axios'
import { formatDate, formatRupiah } from '@/lib/format'
import { onMounted, ref, watch } from 'vue'
import { toast } from 'vue-sonner'
import { confirmDialog } from '@/lib/swal'

const dataTransactions = ref([])
const pageActive = ref(1)
const lastPage = ref(1)
const totalItems = ref(0)
const isLoading = ref(false)
const searchActive = ref('')
const paymentStatus = ref('')

const fetchFines = async (page = 1) => {
  isLoading.value = true
  try {
    const params = {
      page: page,
      has_fine: 1,
      search: searchActive.value,
    }
    
    if (paymentStatus.value === 'paid') {
      params.fine_paid = true
    } else if (paymentStatus.value === 'unpaid') {
      params.fine_paid = false
    }

    const ress = await api.get(`transactions`, { params })

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

const payFine = async (transaction) => {
  const result = await confirmDialog('Konfirmasi Pembayaran', `Apakah Anda yakin ingin memproses pembayaran denda sebesar ${formatRupiah(transaction.fine_amount)} untuk transaksi #${transaction.id}?`)
  if (result.isConfirmed) {
    try {
      const ress = await api.post(`transactions/${transaction.id}/pay-fine`)
      toast.success(ress.data.message)
      fetchFines(pageActive.value)
    } catch (error) {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan')
    }
  }
}

const changePage = (page) => {
  if (page >= 1 && page <= lastPage.value) {
    fetchFines(page)
  }
}

watch(paymentStatus, () => {
  fetchFines(1)
})

let searchDebounce = null
watch(searchActive, () => {
  if (searchDebounce) clearTimeout(searchDebounce)

  searchDebounce = setTimeout(() => {
    fetchFines(1)
  }, 500)
})

onMounted(() => {
  fetchFines()
})
</script>

<template>
  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 lg:mb-8 gap-4 pt-10 lg:pt-0">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold">Pembayaran Denda</h1>
        <p class="text-slate-600">Kelola pembayaran denda keterlambatan buku</p>
      </div>
    </div>

    <div class="flex flex-wrap gap-2 sm:gap-4 mb-6">
      <button
        @click="paymentStatus = ''"
        :class="paymentStatus === '' ? 'bg-primary-500/10 text-primary-400' : ''"
        class="px-4 sm:px-6 py-2 sm:py-3 text-slate-600 border border-primary-500/30 rounded-xl font-medium text-sm sm:text-base"
      >
        Semua
      </button>
      <button
        @click="paymentStatus = 'unpaid'"
        :class="paymentStatus === 'unpaid' ? 'bg-error-500/10 text-error-500 border-error-500/30' : 'border-primary-500/30 text-slate-600'"
        class="px-4 sm:px-6 py-2 sm:py-3 border rounded-xl font-medium transition-colors text-sm sm:text-base"
      >
        Belum Dibayar
      </button>
      <button
        @click="paymentStatus = 'paid'"
        :class="paymentStatus === 'paid' ? 'bg-success-500/10 text-success-500 border-success-500/30' : 'border-primary-500/30 text-slate-600'"
        class="px-4 sm:px-6 py-2 sm:py-3 border rounded-xl font-medium transition-colors text-sm sm:text-base"
      >
        Sudah Dibayar
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
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
      <table class="w-full min-w-[750px]">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-600 text-sm">
            <th class="px-6 py-4">ID Transaksi</th>
            <th class="px-6 py-4">Peminjam</th>
            <th class="px-6 py-4">Buku</th>
            <th class="px-6 py-4">Keterlambatan</th>
            <th class="px-6 py-4">Jumlah Denda</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr v-if="isLoading">
            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Memuat data...</td>
          </tr>
          <tr v-else-if="dataTransactions.length === 0">
            <td colspan="7" class="px-6 py-8 text-center text-slate-500">Tidak ada denda yang ditemukan.</td>
          </tr>
          <tr
            v-for="(transaction, index) in dataTransactions"
            :key="index"
            class="hover:bg-slate-100 transition-colors"
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
            <td class="px-6 py-4 text-error-500 font-medium">
              {{ transaction.days_late }} hari
            </td>
            <td class="px-6 py-4 font-bold text-slate-800">
              {{ formatRupiah(transaction.fine_amount) }}
            </td>
            <td class="px-6 py-4">
              <span
                v-if="transaction.fine_paid"
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm font-medium"
              >
                Lunas
              </span>
              <span
                v-else
                class="px-3 py-1 bg-error-500/20 text-error-500 rounded-full text-sm font-medium"
              >
                Belum Dibayar
              </span>
            </td>
            <td class="px-6 py-4 text-center">
              <button
                v-if="!transaction.fine_paid"
                @click="payFine(transaction)"
                class=" text-white px-4 py-2 bg-primary-500 text-slate-800 rounded-lg hover:bg-primary-600 transition-colors font-medium text-sm"
              >
                Proses Bayar
              </button>
              <span v-else class="text-slate-400 italic text-sm">
                Selesai
              </span>
            </td>
          </tr>
        </tbody>
      </table>
      </div>

      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 sm:px-6 py-4 border-t border-slate-200 gap-3">
        <p class="text-sm text-slate-600">
          Halaman {{ pageActive }} dari {{ lastPage }}
          <span v-if="totalItems">({{ totalItems }} total denda)</span>
        </p>

        <div class="flex flex-wrap gap-2">
          <button
            @click="changePage(pageActive - 1)"
            :disabled="pageActive === 1"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === 1 }"
            class="px-3 sm:px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors text-sm"
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
            class="px-3 sm:px-4 py-2 rounded-lg transition-colors text-sm hidden sm:inline-flex"
          >
            {{ page }}
          </button>

          <button
            @click="changePage(pageActive + 1)"
            :disabled="pageActive === lastPage"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === lastPage }"
            class="px-3 sm:px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors text-sm"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </main>
</template>
