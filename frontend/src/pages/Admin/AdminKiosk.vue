<script setup>
import { onMounted, onUnmounted, ref, watch } from 'vue'
import axios from 'axios'
import api from '@/lib/axios'
import { formatDate } from '@/lib/format'
import router from '@/router'

const formatDateLong = (d) => {
  const date = new Date(d)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  })
}

const formatTime = (d) => {
  const date = new Date(d)
  return date.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  })
}

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

const INACTIVITY_LIMIT = 60 * 1000 
let logoutTimer = null

const resetInactivityTimer = () => {
  if (logoutTimer) clearTimeout(logoutTimer)
  logoutTimer = setTimeout(() => {
    handleLogout(true) 
  }, INACTIVITY_LIMIT)
}

const setupInactivityListeners = () => {
  const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click']
  events.forEach(event => {
    document.addEventListener(event, resetInactivityTimer)
  })
}

const removeInactivityListeners = () => {
  const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click']
  events.forEach(event => {
    document.removeEventListener(event, resetInactivityTimer)
  })
}

const openBorrowModal = (book) => {
  selectedBook.value = book
  showModal.value = true
}

const closeBorrowModal = () => {
  showModal.value = false
  selectedBook.value = null
}

const showReceipt = ref(false)
const receiptData = ref(null)
const showReturnReceipt = ref(false)
const returnReceiptData = ref(null)

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

    receiptData.value = {
      ...ress.data.transaction,
      id: ress.data.transaction.id,
      borrowedAt: ress.data.transaction.borrowed_at,
      dueAt: ress.data.transaction.due_at,
      studentName: student.value.name,
      studentNisn: student.value.nisn,
      bookTitle: selectedBook.value.title,
      bookAuthor: selectedBook.value.author,
      bookIsbn: selectedBook.value.isbn,
      schoolName: student.value.school_name || 'SmartLib AI Library',
      duration: selectedDuration.value
    }
    showReceipt.value = true

    await fetchDashboardData()
    closeBorrowModal()
  } catch (error) {
    console.error('Borrow Error:', error)
    alert(error.response?.data?.message || 'Gagal meminjam buku')
  }
}

const closeReceipt = () => {
  showReceipt.value = false
  receiptData.value = null
}

const closeReturnReceipt = () => {
  showReturnReceipt.value = false
  returnReceiptData.value = null
}

const printReceipt = () => {
  const r = receiptData.value
  const printWindow = window.open('', '_blank', 'width=450,height=600')
  printWindow.document.write(`
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk Peminjaman</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { 
    background: #eee; 
    display: flex; 
    justify-content: center; 
    padding: 20px 0;
  }
  .paper { 
    font-family: 'Courier New', Courier, monospace; 
    font-size: 12px; 
    width: 58mm; 
    padding: 3mm; 
    background: white; 
    color: black; 
    line-height: 1.4;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .header { text-align: center; margin-bottom: 10px; }
  .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
  .header p { font-size: 11px; margin-bottom: 2px; }
  .divider { text-align: center; border-bottom: 1px dashed black; margin: 8px 0; width: 100%; }
  .section { margin: 8px 0; }
  .row { display: flex; justify-content: space-between; margin: 3px 0; }
  .label { color: #333; }
  .text-center { text-align: center; }
  .font-bold { font-weight: bold; }
  .book-title { font-weight: bold; margin: 5px 0; text-align: center; font-size: 13px; text-transform: uppercase; }
  .due-date-box { 
    text-align: center; 
    margin: 12px 0; 
    padding: 8px; 
    border: 1.5px solid black; 
    border-radius: 4px;
  }
  .due-date-box .title { font-size: 10px; font-weight: bold; margin-bottom: 3px; }
  .due-date-box .date { font-size: 15px; font-weight: bold; }
  .footer { text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed black; padding-top: 8px; }
  @media print { 
    body { background: white; padding: 0; display: block; }
    .paper { width: 58mm; box-shadow: none; padding: 2mm; margin: 0 auto; }
    @page { size: 58mm auto; margin: 0; } 
  }
</style>
</head>
<body>
  <div class="paper">
    <div class="header">
      <h1>SMARTLIB AI</h1>
      <p>PERPUSTAKAAN SEKOLAH</p>
      <p>${r.schoolName}</p>
    </div>

    <div class="divider"></div>

    <div class="section">
      <div class="row"><span class="label">No. Transaksi</span><span>#TRX-${String(r.id).padStart(6, '0')}</span></div>
      <div class="row"><span class="label">Tanggal</span><span>${new Date(r.borrowedAt).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}</span></div>
      <div class="row"><span class="label">Waktu</span><span>${new Date(r.borrowedAt).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span></div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <div class="text-center label">PEMINJAM:</div>
      <div class="text-center font-bold" style="font-size: 13px; margin: 3px 0;">${r.studentName}</div>
      <div class="text-center" style="font-size: 11px;">NISN: ${r.studentNisn}</div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <p class="text-center label">BUKU YANG DIPINJAM:</p>
      <p class="book-title">${r.bookTitle}</p>
      <div class="row"><span class="label">Penulis</span><span>${r.bookAuthor || '-'}</span></div>
      <div class="row"><span class="label">ISBN</span><span>${r.bookIsbn || '-'}</span></div>
      <div class="row"><span class="label">Durasi</span><span>${r.duration} hari</span></div>
    </div>

    <div class="due-date-box">
      <div class="title">WAJIB KEMBALI PADA:</div>
      <div class="date">${new Date(r.dueAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).toUpperCase()}</div>
    </div>

    <div class="footer">
      <p style="margin-bottom: 5px;">* Denda telat: Rp 1.000/hari</p>
      <p>Terima kasih telah membaca!</p>
      <p>Simpan struk sebagai bukti sah</p>
      <p style="margin-top: 5px;">Powered by SmartLib AI</p>
    </div>
  </div>
</body>
</html>
  `)
  printWindow.document.close()
  printWindow.focus()
  setTimeout(() => { printWindow.print() }, 300)
}

const handleReturn = async (id) => {
  try {
    const token = localStorage.getItem('student_token')
    const ress = await axios.post(
      `http://localhost:8000/api/transactions/${id}/return`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    )

    if (ress.data && ress.data.data) {
      returnReceiptData.value = ress.data.data
      showReturnReceipt.value = true

      await fetchDashboardData()
    } else {
      throw new Error('Data pengembalian tidak valid')
    }

  } catch (error) {
    console.error('Return Error:', error)
    const errorMsg = error.response?.data?.message || error.message || 'Gagal mengembalikan buku'
    alert(errorMsg)
  }
}

const printReturnReceipt = () => {
  const r = returnReceiptData.value
  const printWindow = window.open('', '_blank', 'width=450,height=600')
  printWindow.document.write(`
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Struk Pengembalian</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { 
    background: #eee; 
    display: flex; 
    justify-content: center; 
    padding: 20px 0;
  }
  .paper { 
    font-family: 'Courier New', Courier, monospace; 
    font-size: 12px; 
    width: 58mm; 
    padding: 3mm; 
    background: white; 
    color: black; 
    line-height: 1.4;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }
  .header { text-align: center; margin-bottom: 10px; }
  .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
  .header p { font-size: 11px; margin-bottom: 2px; }
  .divider { text-align: center; border-bottom: 1px dashed black; margin: 8px 0; width: 100%; }
  .section { margin: 8px 0; }
  .row { display: flex; justify-content: space-between; margin: 3px 0; }
  .label { color: #333; }
  .text-center { text-align: center; }
  .font-bold { font-weight: bold; }
  .book-title { font-weight: bold; margin: 5px 0; text-align: center; font-size: 13px; text-transform: uppercase; }
  .status-box { 
    text-align: center; 
    margin: 12px 0; 
    padding: 8px; 
    border: 2px solid black; 
    background: #000;
    color: #fff;
    font-weight: bold;
    font-size: 14px;
  }
  .footer { text-align: center; margin-top: 15px; font-size: 10px; border-top: 1px dashed black; padding-top: 8px; }
  @media print { 
    body { background: white; padding: 0; display: block; }
    .paper { width: 58mm; box-shadow: none; padding: 2mm; margin: 0 auto; }
    @page { size: 58mm auto; margin: 0; } 
  }
</style>
</head>
<body>
  <div class="paper">
    <div class="header">
      <h1>SMARTLIB AI</h1>
      <p>STRUK PENGEMBALIAN</p>
      <p>${r.school_name}</p>
    </div>

    <div class="divider"></div>

    <div class="section">
      <div class="row"><span class="label">No. Transaksi</span><span>#TRX-${String(r.id).padStart(6, '0')}</span></div>
      <div class="row"><span class="label">Tanggal</span><span>${new Date(r.returned_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}</span></div>
      <div class="row"><span class="label">Waktu</span><span>${new Date(r.returned_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</span></div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <div class="text-center label">PENGEMBALI:</div>
      <div class="text-center font-bold" style="font-size: 13px; margin: 3px 0;">${r.student_name}</div>
      <div class="text-center" style="font-size: 11px;">NISN: ${r.student_nisn}</div>
    </div>

    <div class="divider"></div>

    <div class="section">
      <p class="text-center label">BUKU DIKEMBALIKAN:</p>
      <p class="book-title">${r.book_title}</p>
      <div class="row"><span class="label">Keterlambatan</span><span>${r.days_late} hari</span></div>
      <div class="row"><span class="label">Total Denda</span><span class="font-bold">Rp ${new Intl.NumberFormat('id-ID').format(r.fine_amount)}</span></div>
    </div>

    <div class="status-box">
      DIKEMBALIKAN
    </div>

    <div class="footer">
      <p>Terima kasih telah tertib!</p>
      <p>Simpan struk sebagai bukti sah</p>
      <p style="margin-top: 5px;">Powered by SmartLib AI</p>
    </div>
  </div>
</body>
</html>
  `)
  printWindow.document.close()
  printWindow.focus()
  setTimeout(() => { printWindow.print() }, 300)
}

const handleLogout = async (isAuto = false) => {
  try {
    const token = localStorage.getItem('student_token')
    if (token) {
      await axios.post(
        `http://localhost:8000/api/auth/logout`,
        {},
        {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        },
      )
    }
    
    if (!isAuto) {
      alert('Berhasil keluar')
    } else {
      console.log('Auto logout activated due to inactivity')
    }
    
    localStorage.removeItem('student_token')
    router.push({ name: 'AdminLoginKiosk' })
  } catch (error) {
    console.error('Logout error:', error)
    localStorage.removeItem('student_token')
    router.push({ name: 'AdminLoginKiosk' })
  }
}

const defaultCover = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200'
const getBookCover = (book) => {
  return book?.cover_image_url || defaultCover
}

let timer
onMounted(() => {
  updateDateTime()
  timer = setInterval(updateDateTime, 1000)
  fetchDashboardData()
  
  setupInactivityListeners()
  resetInactivityTimer()
})

onUnmounted(() => {
  clearInterval(timer)
  if (logoutTimer) clearTimeout(logoutTimer)
  removeInactivityListeners()
})
</script>

<template>
  <div class="text-slate-800">
    <div class="min-h-screen flex flex-col bg-slate-50">
      <header class="flex justify-between items-center p-6 bg-white/50 border-b border-slate-200 backdrop-blur-md">
        <div class="flex items-center gap-3">
          <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
          </svg>
          <div>
            <h1 class="text-xl font-bold">LibSmart <span class="text-primary-600">AI</span></h1>
            <p class="text-sm text-slate-600">{{ student?.school_name }}</p>
          </div>
        </div>

        <div class="flex items-center gap-6">
          <div v-if="student" class="flex items-center gap-3 bg-white border border-slate-200 rounded-xl px-4 py-2">
            <div
              class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center font-bold text-lg text-white">
              {{ student.name.charAt(0) }}
            </div>
            <div>
              <p class="font-medium">{{ student.name }}</p>
              <p class="text-xs text-slate-600">NISN: {{ student.nisn }}</p>
            </div>
          </div>
          <div class="text-right">
            <div id="time" class="text-4xl font-bold font-mono">{{ currentTime }} WIB</div>
            <div class="text-slate-600 text-sm">{{ currentDate }}</div>
          </div>
        </div>
      </header>

      <main class="flex-1 p-6 overflow-hidden">
        <div class="grid grid-cols-2 gap-6 h-full">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col overflow-hidden">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold">Katalog Buku</h2>
              <div class="relative">
                <input v-model="searchQuery" type="text" placeholder="Cari buku..."
                  class="pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm w-64 focus:border-primary-500 focus:outline-none transition-all" />
              </div>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
              <div class="grid grid-cols-3 gap-4">
                <button v-for="book in books" :key="book.id" @click="openBorrowModal(book)"
                  class="bg-slate-50 border border-slate-200 rounded-xl p-3 hover:border-primary-500 transition-all duration-300 group text-left relative overflow-hidden">
                  <div class="aspect-[3/4] bg-primary-500/10 rounded-lg mb-2 overflow-hidden">
                    <img :src="getBookCover(book)"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                  </div>
                  <p class="font-medium text-sm line-clamp-2 group-hover:text-primary-400 transition-colors">
                    {{ book.title }}
                  </p>
                  <p class="text-xs text-slate-600">{{ book.author }}</p>

                  <div
                    class="absolute inset-0 bg-primary-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <span
                      class="bg-primary-500 text-[10px] px-2 py-1 rounded-full font-bold uppercase tracking-wider text-white">Klik
                      untuk Pinjam</span>
                  </div>
                </button>
              </div>
            </div>
          </div>

          <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col overflow-hidden">
            <h2 class="text-lg font-bold mb-4">Pinjaman Aktif</h2>
            <div class="flex-1 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
              <div v-for="loan in activeLoans" :key="loan.id"
                class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-xl p-4">
                <div class="w-16 h-20 bg-primary-500/20 rounded-lg overflow-hidden flex-shrink-0">
                  <img :src="getBookCover(loan.book)" class="w-full h-full object-cover" />
                </div>
                <div class="flex-1">
                  <p class="font-medium line-clamp-1">{{ loan.book.title }}</p>
                  <span class="text-sm text-warning-400 flex items-center gap-1 mt-1">Kembali: {{
                    formatDate(loan.due_at) }}</span>
                </div>
                <button @click="handleReturn(loan.id)"
                  class="px-4 py-2 bg-success-600/20 text-success-500 border border-success-500/30 rounded-lg font-medium text-sm hover:bg-success-500 hover:text-slate-800">
                  Kembalikan
                </button>
              </div>
            </div>

            <div v-if="student" class="mt-6 pt-6 border-t border-slate-200">
              <div class="p-4 bg-slate-50/50 border border-slate-200 rounded-xl">
                <div class="flex items-center justify-between text-sm mb-3">
                  <span class="text-slate-600">Kapasitas Pinjam</span>
                  <span class="font-mono">{{ student.current_loans_count }} / {{ student.loan_limit }}</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                  <div class="bg-primary-600 h-full transition-all text-white" :style="{
                    width: (student.current_loans_count / student.loan_limit) * 100 + '%',
                  }"></div>
                </div>
              </div>
              <button @click="handleLogout"
                class="w-full mt-4 py-3 border border-slate-200 rounded-xl text-slate-600 hover:border-red-500 hover:text-red-500">
                Keluar dari Sistem
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <div v-if="showModal && selectedBook"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 backdrop-blur-sm px-4">
      <div
        class="bg-white border border-slate-200 rounded-2xl p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in duration-300">
        <h3 class="text-xl font-bold mb-4 text-center">Konfirmasi Peminjaman</h3>

        <div class="flex items-center gap-4 bg-slate-50 rounded-xl p-4 mb-4 border border-slate-200">
          <div class="w-20 h-28 bg-primary-500/20 rounded-lg overflow-hidden shadow-lg">
            <img :src="getBookCover(selectedBook)" class="w-full h-full object-cover" />
          </div>
          <div class="flex-1">
            <p class="font-bold text-lg leading-tight">{{ selectedBook.title }}</p>
            <p class="text-sm text-slate-600 mt-1">{{ selectedBook.author }}</p>
          </div>
        </div>

        <div v-if="selectedBook.summary" class="mb-6 p-3 bg-slate-50 border border-slate-200 rounded-xl">
          <p class="text-xs text-slate-600 font-semibold mb-1">Ringkasan</p>
          <p class="text-sm text-slate-700 leading-relaxed line-clamp-3">{{ selectedBook.summary }}</p>
        </div>

        <div class="mb-6">
          <label class="text-xs text-slate-600 uppercase tracking-wider font-semibold mb-3 block">
            Pilih Durasi Pinjam
          </label>
          <div class="grid grid-cols-3 gap-2">
            <button v-for="day in [3, 7, 14]" :key="day" @click="selectedDuration = day" :class="selectedDuration === day
              ? 'bg-primary-500 border-primary-500 text-slate-800 shadow-lg shadow-primary-500/20'
              : 'bg-slate-50 border-slate-200 text-slate-600 hover:border-slate-600'
              " class="py-2 border rounded-xl text-sm font-bold transition-all duration-300">
              {{ day }} Hari
            </button>
          </div>
          <p class="text-[10px] text-slate-600 mt-3 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Buku harus dikembalikan sebelum jam operasional berakhir.
          </p>
        </div>

        <div class="flex gap-4">
          <button @click="closeBorrowModal"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors font-medium text-slate-600">
            Batal
          </button>
          <button @click="handleBorrow"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-bold shadow-lg shadow-primary-500/20 hover:scale-[1.02] active:scale-95 transition-all text-white">
            Pinjam Sekarang
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Struk Peminjaman -->
    <div v-if="showReceipt && receiptData"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 backdrop-blur-sm px-4">
      <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
        <h3 class="text-xl font-bold mb-4 text-center text-slate-800">Peminjaman Berhasil! </h3>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 font-mono text-xs text-slate-800 mb-4">
          <div class="text-center border-b border-dashed border-slate-300 pb-3 mb-3">
            <p class="font-bold text-sm">PERPUSTAKAAN</p>
            <p class="text-slate-600 uppercase">{{ receiptData.schoolName }}</p>
          </div>

          <div class="space-y-1 mb-3">
            <div class="flex justify-between"><span class="text-slate-500">No. Transaksi</span><span>#TRX-{{
              String(receiptData.id).padStart(6, '0') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Tanggal</span><span>{{
              formatDateLong(receiptData.borrowedAt) }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Waktu</span><span>{{
              formatTime(receiptData.borrowedAt) }}</span></div>
          </div>

          <div class="border-t border-dashed border-slate-300 pt-3 mb-3 space-y-1">
            <div class="flex justify-between"><span class="text-slate-500">Peminjam</span><span>{{
              receiptData.studentName }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">NISN</span><span>{{ receiptData.studentNisn
            }}</span></div>
          </div>

          <div class="border-t border-dashed border-slate-300 pt-3 mb-3">
            <p class="text-slate-500 mb-1">Buku yang dipinjam:</p>
            <p class="font-bold text-sm uppercase">{{ receiptData.bookTitle }}</p>
            <div class="flex justify-between mt-1"><span class="text-slate-500">Penulis</span><span>{{
              receiptData.bookAuthor || '-' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Durasi</span><span>{{ receiptData.duration }}
                hari</span></div>
          </div>

          <div class="border-2 border-slate-800 rounded-lg p-3 text-center mt-3">
            <p class="text-[10px] text-slate-500 mb-1">BATAS PENGEMBALIAN</p>
            <p class="font-bold text-base">{{ formatDateLong(receiptData.dueAt) }}</p>
          </div>

          <p class="text-center text-[10px] text-slate-500 mt-3">* Denda keterlambatan Rp 1.000/hari</p>
        </div>

        <div class="flex gap-3">
          <button @click="closeReceipt"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 font-medium text-slate-600">
            Tutup
          </button>
          <button @click="printReceipt"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-bold text-white flex items-center justify-center gap-2 hover:opacity-90">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Struk
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Struk Pengembalian -->
    <div v-if="showReturnReceipt && returnReceiptData"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 backdrop-blur-sm px-4">
      <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl">
        <div class="text-center mb-4">
          <div class="w-16 h-16 bg-success-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
            <svg class="w-8 h-8 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800">Buku Dikembalikan!</h3>
          <p class="text-slate-500 text-sm">Terima kasih telah mengembalikan tepat waktu</p>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 font-mono text-xs text-slate-800 mb-4">
          <div class="text-center border-b border-dashed border-slate-300 pb-3 mb-3">
            <p class="font-bold text-sm">PERPUSTAKAAN</p>
            <p class="text-slate-600 uppercase">{{ returnReceiptData.school_name }}</p>
          </div>

          <div class="space-y-1 mb-3">
            <div class="flex justify-between"><span class="text-slate-500">No. Transaksi</span><span>#TRX-{{
              String(returnReceiptData.id).padStart(6, '0') }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Tanggal</span><span>{{ new
              Date(returnReceiptData.returned_at).toLocaleDateString('id-ID') }}</span></div>
          </div>

          <div class="border-t border-dashed border-slate-300 pt-3 mb-3">
            <p class="text-slate-500 mb-1">Buku:</p>
            <p class="font-bold text-sm uppercase">{{ returnReceiptData.book_title }}</p>
            <div class="flex justify-between mt-2"><span class="text-slate-500">Peminjam</span><span>{{
              returnReceiptData.student_name }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Keterlambatan</span><span>{{
              returnReceiptData.days_late }} hari</span></div>
            <div class="flex justify-between font-bold mt-1 text-danger-500" v-if="returnReceiptData.fine_amount > 0">
              <span>Denda</span><span>Rp {{ new Intl.NumberFormat('id-ID').format(returnReceiptData.fine_amount)
              }}</span>
            </div>
          </div>

          <div class="border-2 border-slate-800 rounded-lg p-2 text-center mt-2 bg-slate-800 text-white font-bold">
            SUDAH DIKEMBALIKAN
          </div>
        </div>

        <div class="flex gap-4 mt-6">
          <button @click="closeReturnReceipt"
            class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-300 rounded-lg shadow-sm hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
            Tutup
          </button>

          <button @click="printReturnReceipt"
            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 active:bg-indigo-800 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Struk
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.text-primary-600 {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

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
