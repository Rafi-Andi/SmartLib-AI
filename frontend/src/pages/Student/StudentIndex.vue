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

        <div
          class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center text-sm font-bold"
        >
          B
        </div>
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
            type="text"
            placeholder="Cari judul, penulis, atau ISBN..."
            class="w-full pl-10 pr-4 py-3 bg-dark-bg border border-dark-border rounded-xl text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500/20"
          />
        </div>
      </div>

      <div class="px-4 pb-4 overflow-x-auto scrollbar-hide">
        <div class="flex gap-2">
          <button
            class="px-4 py-2 bg-primary-500 text-white rounded-full text-sm font-medium whitespace-nowrap"
          >
            Semua
          </button>
          <button
            class="px-4 py-2 bg-dark-bg border border-dark-border text-slate-400 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            Fiksi
          </button>
          <button
            class="px-4 py-2 bg-dark-bg border border-dark-border text-slate-400 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            Sains
          </button>
          <button
            class="px-4 py-2 bg-dark-bg border border-dark-border text-slate-400 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            Sejarah
          </button>
          <button
            class="px-4 py-2 bg-dark-bg border border-dark-border text-slate-400 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            Agama
          </button>
          <button
            class="px-4 py-2 bg-dark-bg border border-dark-border text-slate-400 rounded-full text-sm font-medium whitespace-nowrap hover:border-primary-500 hover:text-primary-400 transition-colors"
          >
            Biografi
          </button>
        </div>
      </div>
    </header>

    <main class="flex-1 p-4 pb-24">
      <section class="mb-8">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-bold">Buku Populer</h2>
          <a href="#" class="text-primary-400 text-sm">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto scrollbar-hide -mx-4 px-4">
          <div class="flex gap-4">
            <div class="w-36 flex-shrink-0">
              <div
                class="aspect-[3/4] bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-xl overflow-hidden mb-2"
              >
                <img
                  src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200"
                  alt="Book Cover"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-medium text-sm line-clamp-2">Laskar Pelangi</h3>
              <p class="text-xs text-slate-400 mt-1">Andrea Hirata</p>
              <div class="flex items-center gap-1 mt-2">
                <span class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >Tersedia</span
                >
              </div>
            </div>

            <div class="w-36 flex-shrink-0">
              <div
                class="aspect-[3/4] bg-gradient-to-br from-accent-500/20 to-primary-500/20 rounded-xl overflow-hidden mb-2"
              >
                <img
                  src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=200"
                  alt="Book Cover"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-medium text-sm line-clamp-2">Negeri 5 Menara</h3>
              <p class="text-xs text-slate-400 mt-1">Ahmad Fuadi</p>
              <div class="flex items-center gap-1 mt-2">
                <span class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >Tersedia</span
                >
              </div>
            </div>

            <div class="w-36 flex-shrink-0">
              <div
                class="aspect-[3/4] bg-gradient-to-br from-warning-500/20 to-error-500/20 rounded-xl overflow-hidden mb-2"
              >
                <img
                  src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=200"
                  alt="Book Cover"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-medium text-sm line-clamp-2">Bumi Manusia</h3>
              <p class="text-xs text-slate-400 mt-1">Pramoedya A.T.</p>
              <div class="flex items-center gap-1 mt-2">
                <span class="px-2 py-0.5 bg-error-500/20 text-error-500 rounded text-xs"
                  >Dipinjam</span
                >
              </div>
            </div>

            <div class="w-36 flex-shrink-0">
              <div
                class="aspect-[3/4] bg-gradient-to-br from-success-500/20 to-accent-500/20 rounded-xl overflow-hidden mb-2"
              >
                <img
                  src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200"
                  alt="Book Cover"
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-medium text-sm line-clamp-2">Pulang</h3>
              <p class="text-xs text-slate-400 mt-1">Tere Liye</p>
              <div class="flex items-center gap-1 mt-2">
                <span class="px-2 py-0.5 bg-success-500/20 text-success-500 rounded text-xs"
                  >Tersedia</span
                >
              </div>
            </div>
          </div>
        </div>
      </section>

      <section>
        <h2 class="text-lg font-bold mb-4">Semua Buku</h2>

        <div class="grid grid-cols-2 gap-4">
          <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden">
            <div class="aspect-[3/4] bg-dark-bg">
              <img
                src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=200"
                alt="Book"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-3">
              <h3 class="font-medium text-sm line-clamp-2 mb-1">Laskar Pelangi</h3>
              <p class="text-xs text-slate-400 mb-2">Andrea Hirata</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-success-500">5 tersedia</span>
                <button class="p-2 bg-primary-500 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden">
            <div class="aspect-[3/4] bg-dark-bg">
              <img
                src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=200"
                alt="Book"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-3">
              <h3 class="font-medium text-sm line-clamp-2 mb-1">Negeri 5 Menara</h3>
              <p class="text-xs text-slate-400 mb-2">Ahmad Fuadi</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-success-500">3 tersedia</span>
                <button class="p-2 bg-primary-500 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden opacity-60">
            <div class="aspect-[3/4] bg-dark-bg relative">
              <img
                src="https://images.unsplash.com/photo-1532012197267-da84d127e765?w=200"
                alt="Book"
                class="w-full h-full object-cover"
              />
              <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="px-3 py-1 bg-error-500 rounded-full text-xs font-medium">Habis</span>
              </div>
            </div>
            <div class="p-3">
              <h3 class="font-medium text-sm line-clamp-2 mb-1">Bumi Manusia</h3>
              <p class="text-xs text-slate-400 mb-2">Pramoedya A.T.</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-error-500">0 tersedia</span>
                <button class="p-2 bg-dark-border rounded-lg" disabled>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="bg-dark-card border border-dark-border rounded-xl overflow-hidden">
            <div class="aspect-[3/4] bg-dark-bg">
              <img
                src="https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=200"
                alt="Book"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="p-3">
              <h3 class="font-medium text-sm line-clamp-2 mb-1">Pulang</h3>
              <p class="text-xs text-slate-400 mb-2">Tere Liye</p>
              <div class="flex items-center justify-between">
                <span class="text-xs text-success-500">2 tersedia</span>
                <button class="p-2 bg-primary-500 rounded-lg">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
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