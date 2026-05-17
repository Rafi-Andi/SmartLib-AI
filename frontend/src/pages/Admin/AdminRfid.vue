<script setup>
import api from '@/lib/axios'
import { onMounted, ref, watch } from 'vue'
import { toast } from 'vue-sonner'

const dataUsers = ref(null)
const searchName = ref('')
const isRfid = ref(true)
const uidInput = ref('')

const fetchUsers = async () => {
  const ress = await api.get('users', {
    params: {
      search: searchName.value,
      is_rfid: isRfid.value,
    },
  })
  dataUsers.value = ress.data.data.data
}
const userActive = ref(null)

watch([searchName, isRfid], () => {
  fetchUsers()
})

const updateRfid = async (id) => {
  try {
    const ress = await api.put(`users/${id}`, {
      rfid_uid: uidInput.value,
    })
    toast.success(ress.data.message)
    fetchUsers()
    userActive.value = null
  } catch (error) {
    toast.error(error.response.data.message)
  }
}

onMounted(() => {
  fetchUsers()
})
</script>

<template>
  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="mb-6 lg:mb-8 pt-10 lg:pt-0">
      <h1 class="text-2xl sm:text-3xl font-bold">Aktivasi Kartu RFID</h1>
      <p class="text-slate-600">Hubungkan kartu RFID dengan akun siswa</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
      <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
          <span
            class="w-10 h-10 bg-primary-500/20 text-primary-400 rounded-xl flex items-center justify-center font-bold"
            >1</span
          >
          <h2 class="text-lg font-bold">Pilih Siswa</h2>
        </div>

        <div class="relative mb-4">
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
            type="text"
            v-model="searchName"
            placeholder="Cari nama atau NISN siswa..."
            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
          />
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-600 mb-4">
          <input v-model="isRfid" type="checkbox" checked class="w-4 h-4 rounded" />
          Hanya tampilkan yang belum punya RFID
        </label>

        <div class="space-y-2 max-h-80 overflow-y-auto">
          <button
            @click="userActive = user"
            v-for="(user, index) in dataUsers"
            :key="index"
            class="w-full flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl hover:border-primary-500 text-left transition-colors"
          >
            <div
              class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center font-bold text-lg text-white"
            >
              {{ user?.name[0] }}
            </div>
            <div class="flex-1">
              <p class="font-medium">{{ user?.name }}</p>
              <p class="text-sm text-slate-600">NISN: {{ user?.nisn }}</p>
            </div>
            <span
              v-if="user?.rfid_uid"
              class="px-3 py-1 bg-green-500/20 text-green-500 rounded-full text-xs"
              >Sudah RFID</span
            >
            <span v-else class="px-3 py-1 bg-warning-500/20 text-warning-500 rounded-full text-xs"
              >Belum RFID</span
            >
          </button>
        </div>
      </div>

      <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-6">
          <span
            class="w-10 h-10 bg-accent-500/20 text-accent-400 rounded-xl flex items-center justify-center font-bold"
            >2</span
          >
          <h2 class="text-lg font-bold">Tempel Kartu RFID</h2>
        </div>

        <div
          v-if="userActive"
          class="bg-primary-500/10 border border-primary-500/30 rounded-xl p-4 mb-6"
        >
          <p class="text-sm text-primary-400 mb-2">Siswa Terpilih</p>
          <div class="flex items-center gap-3">
            <div
              class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center font-bold text-white"
            >
              {{ userActive?.name[0] }}
            </div>
            <div>
              <p class="font-medium">{{ userActive?.name }}</p>
              <p class="text-sm text-slate-600">NISN: {{ userActive?.nisn }}</p>
            </div>
          </div>
        </div>

        <div class="flex flex-col items-center py-8">
          <div class="relative mb-6">
            <div
              class="w-24 h-24 bg-primary-600 rounded-2xl flex items-center justify-center pulse text-white"
            >
              <svg
                class="w-12 h-12 text-slate-800"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                />
              </svg>
            </div>
          </div>

          <p class="text-lg font-medium mb-2">Menunggu kartu RFID...</p>
          <p class="text-slate-600 text-center">
            Tempelkan kartu RFID pada reader untuk mengaktifkan
          </p>
        </div>

        <div class="flex items-center my-6">
          <div class="flex-1 border-t border-slate-200"></div>
          <span class="px-4 text-slate-600 text-sm">atau</span>
          <div class="flex-1 border-t border-slate-200"></div>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
          <p class="text-sm text-slate-600 mb-3">Input UID Manual</p>
          <form @submit.prevent="updateRfid(userActive?.id)">
            <div class="flex gap-2">
              <input
                v-model="uidInput"
                type="text" 
                placeholder="Contoh: A1B2C3D4"
                class="flex-1 px-4 py-3 bg-white border border-slate-200 rounded-xl uppercase font-mono focus:border-primary-500 focus:outline-none"
              />
              <button
                class="px-4 py-3 bg-primary-500 rounded-xl font-medium hover:opacity-90 text-white"
              >
                Terapkan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div
      id="successState"
      class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden"
    >
      <div class="bg-white border border-slate-200 rounded-2xl p-8 max-w-md text-center">
        <div
          class="w-20 h-20 bg-success-500/20 rounded-full flex items-center justify-center mx-auto mb-6"
        >
          <svg
            class="w-10 h-10 text-success-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 13l4 4L19 7"
            />
          </svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">RFID Berhasil Diaktivasi!</h2>
        <p class="text-slate-600 mb-6">Kartu RFID sudah terhubung dengan akun siswa</p>

        <div class="bg-slate-50 rounded-xl p-4 mb-6">
          <p class="text-sm text-slate-600">UID Kartu</p>
          <p class="text-xl font-mono font-bold text-accent-400">A1B2C3D4</p>
        </div>

        <button class="w-full py-3 bg-primary-500 rounded-xl font-semibold text-white">
          Aktivasi Kartu Lainnya
        </button>
      </div>
    </div>
  </main>
</template>

<style scoped>
body {
  background: #f8fafc;
}
.pulse {
  animation: pulse 2s ease-in-out infinite;
}
@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.8;
  }
}
</style>
