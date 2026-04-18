<script setup>
import api from '@/lib/axios'
import { onMounted, ref, watch } from 'vue'

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

watch([categoryActive, searchActive], () => {
  pageActive.value = 1
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
        <p class="text-slate-400">Kelola koleksi perpustakaan</p>
      </div>
      <button
        class="px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold flex items-center gap-2 hover:opacity-90"
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
          class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
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
          class="w-full pl-12 pr-4 py-3 bg-dark-card border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
      <select
        v-model="categoryActive"
        class="px-4 py-3 bg-dark-card border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
      >
        <option value="">Semua Kategori</option>
        <option :value="category" v-for="(category, index) in dataCategories" :key="index">
          {{ category }}
        </option>
      </select>
    </div>

    <!-- Books Table -->
    <div class="bg-dark-card border border-dark-border rounded-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-dark-bg">
          <tr class="text-left text-slate-400 text-sm">
            <th class="px-6 py-4">Buku</th>
            <th class="px-6 py-4">ISBN</th>
            <th class="px-6 py-4">Kategori</th>
            <th class="px-6 py-4">Stok</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-dark-border">
          <!-- Row 1 -->
          <tr class="hover:bg-dark-hover" v-for="(book, index) in dataBooks" :key="index">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-12 h-16 bg-primary-500/20 rounded-lg overflow-hidden">
                  <img
                    src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=100"
                    class="w-full h-full object-cover"
                  />
                </div>
                <div>
                  <p class="font-medium">{{ book?.title }}</p>
                  <p class="text-sm text-slate-400">{{ book?.author }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-sm font-mono text-slate-400">{{ book?.isbn }}</td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-primary-500/20 text-primary-400 rounded-full text-sm">{{
                book?.category
              }}</span>
            </td>
            <td class="px-6 py-4">
              <span class="text-success-500">{{ book?.borrowed_count }}</span> /
              {{ book?.stock_count }}
            </td>
            <td class="px-6 py-4">
              <span class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Tersedia</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  class="p-2 text-slate-400 hover:text-primary-400 hover:bg-dark-bg rounded-lg"
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
                <button class="p-2 text-slate-400 hover:text-error-500 hover:bg-dark-bg rounded-lg">
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

      <div class="flex items-center justify-between px-6 py-4 border-t border-dark-border">
        <p class="text-sm text-slate-400">
          Halaman {{ pageActive }} dari {{ lastPage }}
          <span v-if="totalItems">({{ totalItems }} total buku)</span>
        </p>

        <div class="flex gap-2">
          <button
            @click="changePage(pageActive - 1)"
            :disabled="pageActive === 1"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === 1 }"
            class="px-4 py-2 border border-dark-border rounded-lg text-slate-400 hover:bg-dark-hover transition-colors"
          >
            Sebelumnya
          </button>

          <button
            v-for="page in lastPage"
            :key="page"
            @click="changePage(page)"
            :class="[
              pageActive === page
                ? 'bg-primary-500 text-white'
                : 'border border-dark-border text-slate-400 hover:bg-dark-hover',
            ]"
            class="px-4 py-2 rounded-lg transition-colors"
          >
            {{ page }}
          </button>

          <button
            @click="changePage(pageActive + 1)"
            :disabled="pageActive === lastPage"
            :class="{ 'opacity-50 cursor-not-allowed': pageActive === lastPage }"
            class="px-4 py-2 border border-dark-border rounded-lg text-slate-400 hover:bg-dark-hover transition-colors"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </main>

  <div
    id="addBookModal"
    class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4 hidden"
  >
    <div
      class="bg-dark-card border border-dark-border rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b border-dark-border">
        <h2 class="text-xl font-bold">Tambah Buku Baru</h2>
        <button class="p-2 text-slate-400 hover:text-white">
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
      <div class="p-6 border-b border-dark-border">
        <div
          class="bg-dark-bg border-2 border-dashed border-dark-border rounded-xl p-8 text-center"
        >
          <svg
            class="w-12 h-12 text-slate-400 mx-auto mb-4"
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
          <p class="text-slate-400 mb-4">
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

      <form class="p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm text-slate-400 mb-2">Judul Buku *</label>
            <input
              type="text"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">Penulis</label>
            <input
              type="text"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">ISBN</label>
            <input
              type="text"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">Penerbit</label>
            <input
              type="text"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">Tahun Terbit</label>
            <input
              type="number"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">Kategori</label>
            <select
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            >
              <option>Pilih Kategori</option>
              <option>Fiksi</option>
              <option>Non-Fiksi</option>
              <option>Sains</option>
              <option>Sejarah</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-slate-400 mb-2">Jumlah Stok *</label>
            <input
              type="number"
              value="1"
              min="1"
              class="w-full px-4 py-3 bg-dark-bg border border-dark-border rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            type="button"
            class="flex-1 py-3 border border-dark-border rounded-xl hover:bg-dark-hover"
          >
            Batal
          </button>
          <button
            type="submit"
            class="flex-1 py-3 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl font-semibold"
          >
            Simpan Buku
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
.text-gradient {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-text-fill-color: transparent;
}
</style>
