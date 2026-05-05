<script setup>
import api from '@/lib/axios'
import axios from 'axios'
import { handleError, onMounted, ref, watch } from 'vue'

const dataCategories = ref(null)
const fetchCategories = async () => {
  const ress = await api.get('/books-categories')
  dataCategories.value = ress.data.categories
}

const categoryActive = ref('')
const pageActive = ref(1)
const lastPage = ref(1)
const dataBooks = ref(null)
const searchActive = ref('')
const totalItems = ref(0)
const fetchBooks = async () => {
  const ress = await api.get('/books', {
    params: {
      per_page: 5,
      page: pageActive.value,
      category: categoryActive.value,
      search: searchActive.value,
    },
  })

  dataBooks.value = ress.data.data.data
  lastPage.value = ress.data.data.last_page
  totalItems.value = ress.data.data.total
}

watch(categoryActive, () => {
  pageActive.value = 1
  fetchBooks()
})
watch(searchActive, () => {
  pageActive.value = 1
  fetchBooks()
})

watch(pageActive, () => {
  fetchBooks()
})

const searchTimeOut = ref(null)
watch(searchActive, (newVal) => {
  if (searchTimeOut.value) clearTimeout(searchTimeOut.value)

  searchTimeOut.value = setTimeout(() => {
    fetchBooks()
  }, 500)
})

const changePage = (page) => {
  if (page >= 1 && page <= lastPage.value) {
    pageActive.value = page
  }
}

const isAddBook = ref(false)
const coverFile = ref(null)
const coverPreview = ref(null)
const extractedCoverUrl = ref(null)
const isSearchingIsbn = ref(false)
const isCameraOpen = ref(false)
const isExtracting = ref(false)
const videoRef = ref(null)
const canvasRef = ref(null)

const startCamera = async () => {
  isCameraOpen.value = true
  setTimeout(async () => {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ 
        video: { facingMode: 'environment' } 
      })
      if (videoRef.value) {
        videoRef.value.srcObject = stream
      }
    } catch (err) {
      alert('Gagal mengakses kamera: ' + err.message)
      isCameraOpen.value = false
    }
  }, 100)
}

const stopCamera = () => {
  if (videoRef.value && videoRef.value.srcObject) {
    const tracks = videoRef.value.srcObject.getTracks()
    tracks.forEach(track => track.stop())
    videoRef.value.srcObject = null
  }
  isCameraOpen.value = false
}

const captureAndExtract = async () => {
  if (!videoRef.value) return
  
  isExtracting.value = true
  
  const canvas = document.createElement('canvas')
  canvas.width = videoRef.value.videoWidth
  canvas.height = videoRef.value.videoHeight
  const ctx = canvas.getContext('2d')
  ctx.drawImage(videoRef.value, 0, 0)
  
  canvas.toBlob(async (blob) => {
    const formData = new FormData()
    formData.append('image', blob, 'cover.jpg')
    
    try {
      const ress = await api.post('/ai-extract-book', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
      const data = ress.data.data
      
      formAddBook.value = {
        ...formAddBook.value,
        title: data.title || '',
        author: data.author || '',
        isbn: data.isbn || '',
        publisher: data.publisher || '',
        year_published: data.year_published || 2026,
        category: data.category || '',
        summary: data.summary || ''
      }
      
      coverPreview.value = URL.createObjectURL(blob)
      coverFile.value = new File([blob], 'captured-cover.jpg', { type: 'image/jpeg' })
      
      alert('Metadata berhasil diekstrak oleh AI!')
      stopCamera()
    } catch (err) {
      console.error('AI Error:', err)
      const errorData = err.response?.data
      let fullMessage = errorData?.message || err.message
      
      if (errorData?.details) {
        fullMessage += '\n\nDetail:\n' + errorData.details.join('\n')
      }
      
      alert('Gagal mengekstrak metadata: ' + fullMessage)
    } finally {
      isExtracting.value = false
    }
  }, 'image/jpeg', 0.8)
}

const fetchBookByIsbn = async () => {
  if (!formAddBook.value.isbn) return alert('Silahkan masukkan ISBN terlebih dahulu')
  
  isSearchingIsbn.value = true
  
  const apiKey = import.meta.env.VITE_GOOGLE_BOOKS_API_KEY
  
  try {
    const url = `https://www.googleapis.com/books/v1/volumes?q=isbn:${formAddBook.value.isbn}&key=${apiKey}`
    const response = await axios.get(url)
    
    if (response.data.totalItems === 0) {
      alert('Buku tidak ditemukan untuk ISBN tersebut')
      return
    }

    const bookInfo = response.data.items[0].volumeInfo
    
    formAddBook.value.title = bookInfo.title || ''
    formAddBook.value.author = bookInfo.authors ? bookInfo.authors.join(', ') : ''
    formAddBook.value.publisher = bookInfo.publisher || ''
    
    if (bookInfo.publishedDate) {
      const year = bookInfo.publishedDate.substring(0, 4)
      formAddBook.value.year_published = parseInt(year)
    }
    
    formAddBook.value.category = bookInfo.categories ? bookInfo.categories[0] : ''
    formAddBook.value.summary = bookInfo.description || ''
    
    if (bookInfo.imageLinks && bookInfo.imageLinks.thumbnail) {
      const imageUrl = bookInfo.imageLinks.thumbnail.replace('http:', 'https:')
      coverPreview.value = imageUrl
      extractedCoverUrl.value = imageUrl
    }
    
    alert('Metadata berhasil ditemukan!')
  } catch (error) {
    console.error(error)
    if (error.response?.status === 429) {
      alert('Limit Google API tercapai. Pastikan API Key benar atau tunggu sebentar.')
    } else {
      alert('Gagal mengambil metadata buku')
    }
  } finally {
    isSearchingIsbn.value = false
  }
}

const defaultCover = 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200'

const getBookCover = (book) => {
  return book?.cover_image_url || defaultCover
}

const handleCoverChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    coverFile.value = file
    coverPreview.value = URL.createObjectURL(file)
  }
}

const resetForm = () => {
  formAddBook.value = {
    title: '',
    author: '',
    isbn: '',
    publisher: '',
    year_published: 2026,
    category: '',
    summary: '',
    stock_count: 5,
  }
  coverFile.value = null
  coverPreview.value = null
  extractedCoverUrl.value = null
}

const formAddBook = ref({
  title: '',
  author: '',
  isbn: '',
  publisher: '',
  year_published: 2026,
  category: '',
  summary: '',
  stock_count: 5,
})

const handleAddBook = async () => {
  try {
    const fd = new FormData()
    fd.append('title', formAddBook.value.title)
    fd.append('author', formAddBook.value.author)
    fd.append('isbn', formAddBook.value.isbn)
    fd.append('publisher', formAddBook.value.publisher)
    fd.append('year_published', formAddBook.value.year_published)
    fd.append('category', formAddBook.value.category)
    fd.append('summary', formAddBook.value.summary)
    fd.append('stock_count', formAddBook.value.stock_count)
    if (coverFile.value) {
      fd.append('cover_image', coverFile.value)
    } else if (extractedCoverUrl.value) {
      fd.append('cover_url', extractedCoverUrl.value)
    }
    const ress = await api.post('/books', fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    alert(ress.data.message)
    resetForm()
    isAddBook.value = false
    fetchBooks()
  } catch (error) {
    alert(error.response.data.message)
  }
}
const handleEditBook = async () => {
  try {
    const fd = new FormData()
    fd.append('_method', 'PUT')
    fd.append('title', formAddBook.value.title)
    fd.append('author', formAddBook.value.author)
    fd.append('isbn', formAddBook.value.isbn)
    fd.append('publisher', formAddBook.value.publisher)
    fd.append('year_published', formAddBook.value.year_published)
    fd.append('category', formAddBook.value.category)
    fd.append('summary', formAddBook.value.summary)
    fd.append('stock_count', formAddBook.value.stock_count)
    if (coverFile.value) {
      fd.append('cover_image', coverFile.value)
    } else if (extractedCoverUrl.value) {
      fd.append('cover_url', extractedCoverUrl.value)
    }
    const ress = await api.post(`books/${editData.value.id}`, fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    alert(ress.data.message)
    resetForm()
    editData.value = null
    fetchBooks()
  } catch (error) {
    alert(error.response.data.message)
  }
}

const handleDelete = async (id) => {
  if (!confirm('Apakah yakin ingin menghapus?')) return
  try {
    const ress = await api.delete(`/books/${id}`)
    alert(ress.data.message)
    fetchBooks()
  } catch (error) {
    alert(error.response.data.message)
  }
}

const editData = ref(null)

onMounted(() => {
  fetchCategories()
  fetchBooks()
})
</script>

<template>
  <main class="flex-1 ml-64 p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-3xl font-bold">Manajemen Buku</h1>
        <p class="text-slate-600">Kelola koleksi perpustakaan</p>
      </div>
      <button
        @click="isAddBook = true"
        class="px-6 py-3 bg-primary-600 rounded-xl font-semibold flex items-center gap-2 hover:opacity-90 text-white"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
          />
        </svg>
        Tambah Buku
      </button>
    </div>

    <!-- Search & Filter -->
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
          placeholder="Cari judul, penulis, atau ISBN..."
          class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
      <select
        v-model="categoryActive"
        class="px-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
      >
        <option value="">Semua Kategori</option>
        <option :value="category" v-for="(category, index) in dataCategories" :key="index">
          {{ category }}
        </option>
      </select>
    </div>

    <!-- Books Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-600 text-sm">
            <th class="px-6 py-4">Buku</th>
            <th class="px-6 py-4">ISBN</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Stok</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <!-- Row 1 -->
          <tr class="hover:bg-slate-100" v-for="(book, index) in dataBooks" :key="index">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-16 bg-primary-500/20 rounded-lg overflow-hidden">
                  <img
                    :src="getBookCover(book)"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div>
                  <p class="font-medium">{{ book?.title }}</p>
                  <p class="text-sm text-slate-600">{{ book?.author }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ book?.isbn }}</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-primary-500/20 text-primary-400 rounded-full text-sm">{{
                book?.category
              }}</span>
            </td>
            <td class="px-6 py-4">
              <span class="">{{ book?.borrowed_count }}</span> /
              {{ book?.stock_count }}
            </td>
            <td class="px-6 py-4">
              <span
                v-if="book?.available_count > 0"
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Tersedia</span
              >
              <span v-else class="px-3 py-1 bg-red-500/20 text-red-500 rounded-full text-sm"
                >Tidak Tersedia</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  @click="
                    () => {
                      editData = book
                      formAddBook = book
                    }
                  "
                  class="p-2 text-slate-600 hover:text-primary-400 hover:bg-slate-50 rounded-lg"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                    />
                  </svg>
                </button>
                <button
                  @click="handleDelete(book?.id)"
                  class="p-2 text-slate-600 hover:text-error-500 hover:bg-slate-50 rounded-lg"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                  </svg>
                </button>
              </div>
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

  <div
    id="addBookModal"
    v-if="isAddBook"
    class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
  >
    <div
      class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold">Tambah Buku Baru</h2>
        <button class="p-2 text-slate-600 hover:text-slate-800" @click="isAddBook = false">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Camera Section -->
      <div class="p-6 border-b border-slate-200">
        <div v-if="!isCameraOpen"
          class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-8 text-center"
        >
          <svg
            class="w-12 h-12 text-slate-600 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
          <p class="text-slate-600 mb-4">
            Scan sampul buku dengan kamera untuk ekstrak metadata menggunakan AI
          </p>
          <button
            type="button"
            @click="startCamera"
            class="px-6 py-3 bg-accent-500 rounded-xl font-medium flex items-center gap-2 mx-auto hover:opacity-90"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
              />
            </svg>
            Buka Kamera
          </button>
        </div>
        
        <div v-else class="relative bg-black rounded-xl overflow-hidden aspect-video">
          <video ref="videoRef" autoplay playsinline class="w-full h-full object-cover"></video>
          <div class="absolute inset-x-0 bottom-4 flex justify-center gap-4">
            <button 
              type="button"
              @click="stopCamera"
              class="px-4 py-2 bg-white/20 backdrop-blur-md text-white rounded-lg hover:bg-white/30"
            >
              Batal
            </button>
            <button 
              type="button"
              @click="captureAndExtract"
              :disabled="isExtracting"
              class="px-6 py-2 bg-primary-500 text-slate-800 rounded-lg font-bold flex items-center gap-2 disabled:opacity-50"
            >
              <svg v-if="isExtracting" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ isExtracting ? 'Mengekstrak...' : 'Ambil Foto & Ekstrak' }}</span>
            </button>
          </div>
        </div>
      </div>

      <form class="p-6 space-y-4" @submit.prevent="handleAddBook">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Cover Buku</label>
            <div class="flex items-center gap-4">
              <div class="w-20 h-28 bg-slate-100 border border-slate-200 rounded-xl overflow-hidden">
                <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
              </div>
              <div class="flex-1">
                <input type="file" accept="image/*" @change="handleCoverChange" class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-500/10 file:text-primary-600 hover:file:bg-primary-500/20" />
                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP. Maks 2MB</p>
              </div>
            </div>
          </div>
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Judul Buku *</label>
            <input
              v-model="formAddBook.title"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Penulis</label>
            <input
              v-model="formAddBook.author"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">ISBN</label>
            <div class="flex gap-2">
              <input
                v-model="formAddBook.isbn"
                type="text"
                class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
                placeholder="Contoh: 978..."
              />
              <button 
                type="button"
                @click="fetchBookByIsbn"
                :disabled="isSearchingIsbn"
                class="px-4 bg-primary-500 text-white rounded-xl hover:opacity-90 disabled:opacity-50 transition-all flex items-center gap-2"
              >
                <svg v-if="isSearchingIsbn" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ isSearchingIsbn ? '...' : 'Cari' }}</span>
              </button>
            </div>
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Penerbit</label>
            <input
              v-model="formAddBook.publisher"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Tahun Terbit</label>
            <input
              v-model="formAddBook.year_published"
              type="number"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Kategori</label>
            <input
              v-model="formAddBook.category"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Jumlah Stok *</label>
            <input
              v-model="formAddBook.stock_count"
              type="number"
              value="1"
              min="1"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Ringkasan / Deskripsi</label>
            <textarea
              v-model="formAddBook.summary"
              rows="3"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none resize-none"
              placeholder="Tulis ringkasan singkat tentang buku ini..."
            ></textarea>
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            @click="isAddBook = false"
            type="button"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-100"
          >
            Batal
          </button>
          <button
            type="submit"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-semibold text-white"
          >
            Simpan Buku
          </button>
        </div>
      </form>
    </div>
  </div>
  <div
    id="editBookModal"
    v-if="editData"
    class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
  >
    <div
      class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold">Edit Buku Baru</h2>
        <button
          class="p-2 text-slate-600 hover:text-slate-800"
          @click="editData = null; resetForm()"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>
      </div>

      <!-- Camera Section -->
      <div class="p-6 border-b border-slate-200">
        <div
          class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-8 text-center"
        >
          <svg
            class="w-12 h-12 text-slate-600 mx-auto mb-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
            />
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
            />
          </svg>
          <p class="text-slate-600 mb-4">
            Scan sampul buku dengan kamera untuk ekstrak metadata menggunakan AI
          </p>
          <button
            class="px-6 py-3 bg-accent-500 rounded-xl font-medium flex items-center gap-2 mx-auto hover:opacity-90"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
              />
            </svg>
            Buka Kamera
          </button>
        </div>
      </div>

      <form class="p-6 space-y-4" @submit.prevent="handleEditBook">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Cover Buku</label>
            <div class="flex items-center gap-4">
              <div class="w-20 h-28 bg-slate-100 border border-slate-200 rounded-xl overflow-hidden">
                <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" />
                <img v-else-if="editData?.cover_image_url" :src="editData.cover_image_url" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center text-slate-400">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
              </div>
              <div class="flex-1">
                <input type="file" accept="image/*" @change="handleCoverChange" class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-500/10 file:text-primary-600 hover:file:bg-primary-500/20" />
                <p class="text-xs text-slate-400 mt-1">JPG, PNG, WEBP. Maks 2MB</p>
              </div>
            </div>
          </div>
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Judul Buku *</label>
            <input
              v-model="formAddBook.title"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Penulis</label>
            <input
              v-model="formAddBook.author"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">ISBN</label>
            <div class="flex gap-2">
              <input
                v-model="formAddBook.isbn"
                type="text"
                class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
              />
              <button 
                type="button"
                @click="fetchBookByIsbn"
                :disabled="isSearchingIsbn"
                class="px-4 bg-primary-500 text-white rounded-xl hover:opacity-90 disabled:opacity-50 transition-all flex items-center gap-2"
              >
                <svg v-if="isSearchingIsbn" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ isSearchingIsbn ? '...' : 'Cari' }}</span>
              </button>
            </div>
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Penerbit</label>
            <input
              v-model="formAddBook.publisher"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Tahun Terbit</label>
            <input
              v-model="formAddBook.year_published"
              type="number"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Kategori</label>
            <input
              v-model="formAddBook.category"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Jumlah Stok *</label>
            <input
              v-model="formAddBook.stock_count"
              type="number"
              value="1"
              min="1"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Ringkasan / Deskripsi</label>
            <textarea
              v-model="formAddBook.summary"
              rows="3"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none resize-none"
              placeholder="Tulis ringkasan singkat tentang buku ini..."
            ></textarea>
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            @click="editData = null; resetForm()"
            type="button"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-100"
          >
            Batal
          </button>
          <button
            type="submit"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-semibold text-white"
          >
            Edit Buku
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.text-primary-600 {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-text-fill-color: transparent;
}
</style>
