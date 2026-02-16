<script setup>
import api from '@/lib/axios'
import { onMounted, ref, watch } from 'vue'

const dataCategories = ref(null)
const fetchCategories = async () => {
  const ress = await api.get('/books-categories')
  dataCategories.value = ress.data.categories
}

const activeCategory = ref('')
const search = ref('')
const pageActive = ref(1)
const lastPage = ref(1)
const dataBooks = ref(null)

const fetchBooks = async () => {
  const ress = await api.get('/books', {
    params: {
      per_page: 3,
    },
  })

  dataBooks.value = ress.data.data.data
}

const dataAllBooks = ref(null)

const fetchAllBooks = async () => {
  const ress = await api.get('/books', {
    params: {
      per_page: 10,
      category: activeCategory.value,
      search: search.value,
      page: pageActive.value,
    },
  })

  dataAllBooks.value = ress.data.data.data
  lastPage.value = ress.data.data.last_page
}

onMounted(() => {
  fetchCategories()
  fetchBooks()
  fetchAllBooks()
})

const bookDetail = ref(null)

watch(activeCategory, () => {
  fetchAllBooks()
  pageActive.value = 1
})

let searchBounce = null
watch(search, () => {
  if (searchBounce) clearTimeout(searchBounce)
  searchBounce = setTimeout(() => {
    fetchAllBooks()
  }, 500)
  pageActive.value = 1
})

watch(pageActive, () => {
  fetchAllBooks()
})

const name = localStorage.getItem('name')
</script>

<template>
  <div class="min-h-screen flex flex-col text-white">
    <header class="sticky top-0 z-50 bg-dark-card/90 backdrop-blur-lg border-b border-dark-border">
      <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-2">
          <svg
            class="w-6 h-6 text-primary-500"
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
          <span class="font-bold">LibSmart</span>
        </div>

        <router-link
          :to="{ name: 'StudentProfile' }"
          class="w-8 h-8 bg-linear-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center text-sm font-bold"
        >
          {{ name[0] }}
        </router-link>
      </div>

      <div class="px-4 pb-4">
        <div class="relative">
          <svg
            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"
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
            v-model="search"
            type="text"
            placeholder="Cari judul, penulis, atau ISBN..."
            class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border rounded-xl text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
          />
        </div>
      </div>

      <div class="px-4 pb-4 overflow-x-auto scrollbar-hide">
        <div class="flex gap-2">
          <button
            @click="activeCategory = ''"
            :class="
              activeCategory === ''
                ? 'bg-primary-500 text-white'
                : 'bg-dark-bg border border-dark-border text-slate-400'
            "
            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap"
          >
            Semua
          </button>
          <button
            v-for="(item, index) in dataCategories"
            :key="index"
            @click="activeCategory = item"
            :class="
              activeCategory === item
                ? 'bg-primary-500 text-white'
                : 'bg-dark-bg border border-dark-border text-slate-400'
            "
            class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            {{ item }}
          </button>
        </div>
      </div>
    </header>

    <main class="flex-1 p-4 pb-24">
      <section class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold">Buku Terbaru</h2>
        </div>

        <div class="overflow-x-auto scrollbar-hide -mx-4 px-4">
          <div class="flex gap-4">
            <div
              @click="
                function () {
                  bookDetail = item
                }
              "
              class="w-36 shrink-0"
              v-for="(item, index) in dataBooks"
              :key="index"
            >
              <div
                class="aspect-3/4 bg-linear-to-br from-primary-500/20 to-accent-500/20 rounded-xl overflow-hidden mb-2"
              >
                <img
                  src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200"
                  alt="Book Cover"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-medium text-sm line-clamp-2">{{ item?.title }}</h3>
              <p class="text-xs text-slate-400 mt-1">{{ item?.author }}</p>
              <div class="flex items-center gap-1 mt-2">
                <span
                  v-if="item?.available_count > 0"
                  class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >Tersedia</span
                >
                <span v-else class="px-2 py-0.5 bg-error-500/20 text-error-500 rounded text-xs"
                  >Dipinjam</span
                >
              </div>
            </div>
          </div>
        </div>
      </section>

      <section>
        <h2 class="text-lg font-bold mb-4">Semua Buku</h2>

        <div class="grid grid-cols-2 gap-4">
          <div
            v-for="(item, index) in dataAllBooks"
            :key="index"
            @click="bookDetail = item"
            class="bg-dark-card border border-dark-border rounded-xl overflow-hidden"
          >
            <div class="aspect-3/4 bg-dark-bg">
              <img
                src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200"
                alt="Book"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-3">
              <h3 class="font-medium text-sm line-clamp-2 mb-1">{{ item?.title }}</h3>
              <p class="text-xs text-slate-400 mb-2">{{ item?.author }}</p>
              <div class="flex items-center justify-between">
                <span
                  v-if="item?.available_count > 0"
                  class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >{{ item?.available_count }} Tersedia</span
                >
                <span v-else class="px-2 py-0.5 bg-error-500/20 text-error-500 rounded text-xs"
                  >{{ item?.available_count }} Tersedia</span
                >
              </div>
            </div>
          </div>
        </div>
      </section>
      <div class="flex items-center justify-center gap-2 mt-8">
        <button
          @click="pageActive--"
          class="cursor-pointer px-3 py-2 bg-dark-bg border border-dark-border rounded-lg text-slate-400 hover:border-primary-500 hover:text-primary-400 disabled:opacity-40"
          :disabled="pageActive === 1"
        >
          Prev
        </button>

        <button
          v-for="(item, index) in lastPage"
          :key="index"
          @click="pageActive = item"
          class="cursor-pointer px-3 py-2 bg-dark-bg border border-dark-border rounded-lg text-slate-400 hover:border-primary-500 hover:text-primary-400"
          :class="{ 'bg-primary-500': pageActive === item, 'text-white': pageActive === item }"
        >
          {{ item }}
        </button>

        <button
          @click="pageActive++"
          class="cursor-pointer px-3 py-2 bg-dark-bg border border-dark-border rounded-lg text-slate-400 hover:border-primary-500 hover:text-primary-400 disabled:opacity-40"
          :disabled="pageActive === lastPage"
        >
          Next
        </button>
      </div>
    </main>
    <div id="bookModal" class="fixed inset-0 z-50 bg-black/80 flex items-end" v-if="bookDetail">
      <div class="bg-dark-card rounded-t-3xl w-full max-h-[80vh] overflow-y-auto">
        <div class="flex justify-center py-3">
          <div class="w-12 h-1 bg-dark-border rounded-full"></div>
        </div>

        <div class="p-6">
          <div class="flex gap-4 mb-6">
            <img
              src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200"
              alt="Book"
              class="w-24 h-36 object-cover rounded-xl"
            />
            <div class="flex-1">
              <h2 class="text-xl font-bold mb-2">{{ bookDetail?.title }}</h2>
              <p class="text-slate-400 mb-2">{{ bookDetail?.author }}</p>
              <p class="text-sm text-slate-500">{{ bookDetail?.isbn }}</p>
              <div class="flex items-center gap-2 mt-3">
                <span
                  v-if="bookDetail?.available_count > 0"
                  class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >{{ bookDetail?.available_count }} Tersedia</span
                >
                <span v-else class="px-2 py-0.5 bg-error-500/20 text-error-500 rounded text-xs"
                  >{{ bookDetail?.available_count }} Tersedia</span
                >
              </div>
            </div>
          </div>

          <div class="mb-6">
            <h3 class="font-medium mb-2">Deskripsi</h3>
            <p class="text-sm text-slate-400 leading-relaxed">
              {{ bookDetail?.summary }}
            </p>
          </div>

          <button
            @click="bookDetail = null"
            class="w-full py-4 bg-linear-to-r from-primary-500 to-accent-500 rounded-xl font-semibold text-lg flex items-center justify-center gap-2"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
body {
  background: #0a0a0f;
}

.text-gradient {
  background: linear-gradient(135deg, #6366f1, #22d3ee);
  -webkit-text-fill-color: transparent;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.shimmer {
  background: linear-gradient(90deg, #1e1e2e 0%, #2a2a3e 50%, #1e1e2e 100%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}
</style>
