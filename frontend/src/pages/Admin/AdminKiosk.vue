<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'
import axios from 'axios'
import api from '@/lib/axios'
import { formatDate } from '@/lib/format'
import router from '@/router'

const selectedDuration = ref(7)

const currentTime = ref('')
const currentDate = ref('')
const isLoading = ref(true)

const student = ref(null)
const books = ref([])
const activeLoans = ref([])

const student_token = localStorage.getItem('student_token')

if (!student_token) {
  router.push({ name: 'AdminLoginKiosk' })
}

const fetchDashboardData = async () => {
  try {
    isLoading.value = true
    const token = localStorage.getItem('student_token')

    const response = await axios.get('http://localhost:8000/api/kiosk', {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    console.log(response)

    const { data } = response.data
    student.value = data.student
    books.value = data.books
    activeLoans.value = data.active_loans
  } catch (error) {
    console.error('Gagal memuat data:', error)
  } finally {
    isLoading.value = false
  }
}

const searchQuery = ref('')

const fetchBooks = async (query = '') => {
  try {
    const token = localStorage.getItem('student_token')
    const response = await axios.get('http://localhost:8000/api/books', {
      params: {
        search: query,
        available_only: true,
      },
      headers: { Authorization: `Bearer ${token}` },
    })

    books.value = response.data.data.data
  } catch (error) {
    console.error('Gagal mencari buku:', error)
  }
}

watch(searchQuery, (newQuery) => {
  if (newQuery.length > 2 || newQuery.length === 0) {
    fetchBooks(newQuery)
  }
})

const updateDateTime = () => {
  const now = new Date()
  currentTime.value = now
    .toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false })
    .replace('.', ':')
  currentDate.value = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
}

const selectedBook = ref(null)
const showModal = ref(false)

const openBorrowModal = (book) => {
  selectedBook.value = book
  showModal.value = true
}

const closeBorrowModal = () => {
  showModal.value = false
  selectedBook.value = null
}

const handleBorrow = async () => {
  try {
    const ress = await axios.post(
      'http://localhost:8000/api/transactions/borrow',
      {
        book_id: selectedBook.value.id,
        due_days: selectedDuration.value,
      },
      {
        headers: {
          Authorization: `Bearer ${student_token}`,
        },
      },
    )

    alert(ress.data.message)
    closeBorrowModal()
    fetchDashboardData()
  } catch (error) {
    alert(error.response.data.message)
  }
}

const handleReturn = async (id) => {
  try {
    const ress = await axios.post(
      `http://localhost:8000/api/transactions/${id}/return`,
      {},
      {
        headers: {
          Authorization: `Bearer ${student_token}`,
        },
      },
    )
    alert(ress.data.message)
    fetchDashboardData()
  } catch (error) {
    alert(error.response.data.message)
  }
}

const handleLogout = async () => {
  try {
    const ress = await axios.post(
      `http://localhost:8000/api/auth/logout`,
      {},
      {
        headers: {
          Authorization: `Bearer ${student_token}`,
        },
      },
    )
    alert(ress.data.message)
    localStorage.removeItem('student_token')
    router.push({name: 'AdminLoginKiosk'})
  } catch (error) {
    alert(error.response.data.message)
  }
}

let timer
onMounted(() => {
  updateDateTime()
  timer = setInterval(updateDateTime, 1000)
  fetchDashboardData()
})

onUnmounted(() => {
  clearInterval(timer)
})
</script>

<template>
  <div class="text-white">
    <div class="min-h-screen flex flex-col bg-dark-bg">
      <header
        class="flex justify-between items-center p-6 bg-dark-card/50 border-b border-dark-border backdrop-blur-md"
      >
        <div class="flex items-center gap-3">
          <svg
            class="w-8 h-8 text-primary-500"
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
          <div>
            <h1 class="text-xl font-bold">LibSmart <span class="text-gradient">AI</span></h1>
            <p class="text-sm text-slate-400">{{ student?.school_name }}</p>
          </div>
        </div>

        <div class="flex items-center gap-6">
          <div
            v-if="student"
            class="flex items-center gap-3 bg-dark-card border border-dark-border rounded-xl px-4 py-2"
          >
            <div
              class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center font-bold text-lg"
            >
              {{ student.name.charAt(0) }}
            </div>
            <div>
              <p class="font-medium">{{ student.name }}</p>
              <p class="text-xs text-slate-400">NISN: {{ student.nisn }}</p>
            </div>
          </div>
          <div class="text-right">
            <div id="time" class="text-4xl font-bold font-mono">{{ currentTime }} WIB</div>
            <div class="text-slate-400 text-sm">{{ currentDate }}</div>
          </div>
        </div>
      </header>

      <main class="flex-1 p-6 overflow-hidden">
        <div class="grid grid-cols-2 gap-6 h-full">
          <div
            class="bg-dark-card border border-dark-border rounded-2xl p-6 flex flex-col overflow-hidden"
          >
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold">Katalog Buku</h2>
              <div class="relative">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari buku..."
                  class="pl-10 pr-4 py-2 bg-dark-bg border border-dark-border rounded-lg text-sm w-64 focus:border-primary-500 focus:outline-none transition-all"
                />
              </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
              <div class="grid grid-cols-3 gap-4">
                <button
                  v-for="book in books"
                  :key="book.id"
                  @click="openBorrowModal(book)"
                  class="bg-dark-bg border border-dark-border rounded-xl p-3 hover:border-primary-500 transition-all duration-300 group text-left relative overflow-hidden"
                >
                  <div class="aspect-[3/4] bg-primary-500/10 rounded-lg mb-2 overflow-hidden">
                    <img
                      :src="book.cover_url"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    />
                  </div>
                  <p
                    class="font-medium text-sm line-clamp-2 group-hover:text-primary-400 transition-colors"
                  >
                    {{ book.title }}
                  </p>
                  <p class="text-xs text-slate-400">{{ book.author }}</p>

                  <div
                    class="absolute inset-0 bg-primary-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                  >
                    <span
                      class="bg-primary-500 text-white text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wider"
                      >Klik untuk Pinjam</span
                    >
                  </div>
                </button>
              </div>
            </div>
          </div>

          <div
            class="bg-dark-card border border-dark-border rounded-2xl p-6 flex flex-col overflow-hidden"
          >
            <h2 class="text-lg font-bold mb-4">Pinjaman Aktif</h2>
            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
              <div
                v-for="loan in activeLoans"
                :key="loan.id"
                class="flex items-center gap-4 bg-dark-bg border border-dark-border rounded-xl p-4"
              >
                <div class="w-16 h-20 bg-primary-500/20 rounded-lg overflow-hidden flex-shrink-0">
                  <img :src="loan.book.cover_url" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1">
                  <p class="font-medium line-clamp-1">{{ loan.book.title }}</p>
                  <span class="text-sm text-warning-400 flex items-center gap-1 mt-1"
                    >Kembali: {{ formatDate(loan.due_at) }}</span
                  >
                </div>
                <button
                  @click="handleReturn(loan.id)"
                  class="px-4 py-2 bg-success-600/20 text-success-500 border border-success-500/30 rounded-lg font-medium text-sm hover:bg-success-500 hover:text-white"
                >
                  Kembalikan
                </button>
              </div>
            </div>

            <div v-if="student" class="mt-6 pt-6 border-t border-dark-border">
              <div class="p-4 bg-dark-bg/50 border border-dark-border rounded-xl">
                <div class="flex items-center justify-between text-sm mb-3">
                  <span class="text-slate-400">Kapasitas Pinjam</span>
                  <span class="font-mono"
                    >{{ student.current_loans_count }} / {{ student.loan_limit }}</span
                  >
                </div>
                <div class="w-full bg-dark-border rounded-full h-2.5 overflow-hidden">
                  <div
                    class="bg-gradient-to-r from-primary-500 to-accent-500 h-full transition-all"
                    :style="{
                      width: (student.current_loans_count / student.loan_limit) * 100 + '%',
                    }"
                  ></div>
                </div>
              </div>
              <button @click="handleLogout" class="w-full mt-4 py-3 border border-dark-border rounded-xl text-slate-400 hover:border-red-500 hover:text-red-500">
                Keluar dari Sistem
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div
      v-if="showModal && selectedBook"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 backdrop-blur-sm px-4"
    >
      <div
        class="bg-dark-card border border-dark-border rounded-2xl p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in duration-300"
      >
        <h3 class="text-xl font-bold mb-4 text-center">Konfirmasi Peminjaman</h3>

        <div
          class="flex items-center gap-4 bg-dark-bg rounded-xl p-4 mb-6 border border-dark-border"
        >
          <div class="w-20 h-28 bg-primary-500/20 rounded-lg overflow-hidden shadow-lg">
            <img :src="selectedBook.cover_url" class="w-full h-full object-cover" />
          </div>
          <div class="flex-1">
            <p class="font-bold text-lg leading-tight">{{ selectedBook.title }}</p>
            <p class="text-sm text-slate-400 mt-1">{{ selectedBook.author }}</p>
          </div>
        </div>

        <div class="mb-6">
          <label class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-3 block">
            Pilih Durasi Pinjam
          </label>
          <div class="grid grid-cols-3 gap-2">
            <button
              v-for="day in [3, 7, 14]"
              :key="day"
              @click="selectedDuration = day"
              :class="
                selectedDuration === day
                  ? 'bg-primary-500 border-primary-500 text-white shadow-lg shadow-primary-500/20'
                  : 'bg-dark-bg border-dark-border text-slate-400 hover:border-slate-600'
              "
              class="py-2 border rounded-xl text-sm font-bold transition-all duration-300"
            >
              {{ day }} Hari
            </button>
          </div>
          <p class="text-[10px] text-slate-500 mt-3 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            Buku harus dikembalikan sebelum jam operasional berakhir.
          </p>
        </div>

        <div class="flex gap-4">
          <button
            @click="closeBorrowModal"
            class="flex-1 py-3 border border-dark-border rounded-xl hover:bg-dark-bg transition-colors font-medium text-slate-400"
          >
            Batal
          </button>
          <button
            @click="handleBorrow"
            class="flex-1 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-bold shadow-lg shadow-primary-500/20 hover:scale-[1.02] active:scale-95 transition-all"
          >
            Pinjam Sekarang
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.text-gradient {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* Custom Scrollbar untuk tampilan Kiosk yang lebih rapi */
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #334155;
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #475569;
}

.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
