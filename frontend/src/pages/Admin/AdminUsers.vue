<script setup>
import api from '@/lib/axios'
import { onMounted, ref, watch } from 'vue'
import { toast } from 'vue-sonner'
import { confirmDialog } from '@/lib/swal'
const dataUsers = ref([])
const roleActive = ref('')
const search = ref('')
const page = ref(1)
const totalData = ref(0)

const lastPage = ref(1)

const fetchUsers = async () => {
  try {
    const ress = await api.get('users', {
      params: {
        role: roleActive.value,
        search: search.value,
        page: page.value,
      },
    })
    dataUsers.value = ress.data.data.data
    totalData.value = ress.data.data.total
    lastPage.value = ress.data.data.last_page
  } catch (error) {
    console.error('Gagal mengambil data:', error)
  }
}
const changePage = (newPage) => {
  if (newPage >= 1 && newPage <= lastPage.value) {
    page.value = newPage
  }
}
watch([search, roleActive], () => {
  page.value = 1
  fetchUsers()
})

watch(page, () => {
  fetchUsers()
})

const formAddUser = ref({
  name: '',
  email: '',
  password: '',
  nisn: '',
  role: '',
  is_active: false,
})

const isAddUser = ref(false)

const handleAddUser = async () => {
  try {
    const ress = await api.post('users', formAddUser.value)
    toast.success(ress.data.message)
    fetchUsers()
    isAddUser.value = false
    clearFormUser()
  } catch (error) {
    toast.error(error.response.data.message)
  }
}

const editData = ref(null)

const handleEditUser = async () => {
  try {
    const ress = await api.put(`users/${editData.value.id}`, formAddUser.value)
    toast.success(ress.data.message)
    fetchUsers()
    editData.value = null
    clearFormUser()
  } catch (error) {
    toast.error(error.response.data.message)
  }
}

const clearFormUser = () => {
  formAddUser.value = {
    name: '',
    email: '',
    password: '',
    nisn: '',
    role: '',
    is_active: false,
  }
}

const handleDelete = async (id) => {
  const result = await confirmDialog('Konfirmasi Hapus', 'Apakah yakin ingin menghapus?')
  if (result.isConfirmed) {
    try {
      const ress = await api.delete(`users/${id}`)
      toast.success(ress.data.message)
      fetchUsers()
    } catch (error) {
      toast.error(error.response.data.message)
    }
  }
}

onMounted(() => {
  fetchUsers()
})
</script>

<template>
  <main class="flex-1 p-4 sm:p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 lg:mb-8 gap-4 pt-10 lg:pt-0">
      <div>
        <h1 class="text-2xl sm:text-3xl font-bold">Manajemen Pengguna</h1>
        <p class="text-slate-600">Kelola siswa dan admin perpustakaan</p>
      </div>
      <button
        @click="isAddUser = true"
        class="px-4 sm:px-6 py-3 bg-primary-600 rounded-xl font-semibold flex items-center gap-2 text-white w-full sm:w-auto justify-center"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
          />
        </svg>
        Tambah Pengguna
      </button>
    </div>

    <div class="flex flex-wrap gap-2 sm:gap-4 mb-6">
      <button
        @click="roleActive = ''"
        :class="
          roleActive === ''
            ? 'bg-primary-500/10 text-primary-400 border-primary-500/30'
            : 'bg-white text-slate-600'
        "
        class="px-4 sm:px-6 py-2 sm:py-3 border rounded-xl font-medium transition-all text-sm sm:text-base"
      >
        Semua
      </button>
      <button
        @click="roleActive = 'student'"
        :class="
          roleActive === 'student'
            ? 'bg-primary-500/10 text-primary-400 border-primary-500/30'
            : 'bg-white text-slate-600'
        "
        class="px-4 sm:px-6 py-2 sm:py-3 border rounded-xl font-medium transition-all text-sm sm:text-base"
      >
        Siswa
      </button>
      <button
        @click="roleActive = 'admin'"
        :class="
          roleActive === 'admin'
            ? 'bg-primary-500/10 text-primary-400 border-primary-500/30'
            : 'bg-white text-slate-600'
        "
        class="px-4 sm:px-6 py-2 sm:py-3 border rounded-xl font-medium transition-all text-sm sm:text-base"
      >
        Admin
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
          v-model="search"
          type="text"
          placeholder="Cari nama, email, atau NISN..."
          class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
        />
      </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
      <div class="overflow-x-auto">
      <table class="w-full min-w-[700px]">
        <thead class="bg-slate-50">
          <tr class="text-left text-slate-600 text-sm">
            <th class="px-6 py-4">Pengguna</th>
            <th class="px-6 py-4">NISN</th>
            <th class="px-6 py-4">Role</th>
            <th class="px-6 py-4">RFID</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          <tr class="hover:bg-slate-100" v-for="(user, index) in dataUsers" :key="index">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center font-bold text-white"
                >
                  {{ user?.name[0] }}
                </div>
                <div>
                  <p class="font-medium">{{ user?.name }}</p>
                  <p class="text-sm text-slate-600">{{ user?.email }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 font-mono text-sm">{{ user?.nisn }}</td>
            <td class="px-6 py-4">
              <span
                v-if="user?.role === 'student'"
                class="px-3 py-1 bg-accent-500/20 text-accent-400 rounded-full text-sm"
                >{{ user?.role }}</span
              >
              <span
                v-else
                class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm"
                >{{ user?.role }}</span
              >
            </td>
            <td class="px-6 py-4">
              <span
                v-if="user?.rfid_uid"
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm flex items-center gap-1 w-fit"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
                Aktif
              </span>
              <span
                v-else
                class="px-3 py-1 bg-yellow-500/20 text-yellow-500 rounded-full text-sm flex items-center gap-1 w-fit"
              >
                Belum Aktif
              </span>
            </td>
            <td class="px-6 py-4">
              <span
                v-if="user?.is_active"
                class="px-3 py-1 bg-success-500/20 text-success-500 rounded-full text-sm"
                >Aktif</span
              >
              <span v-else class="px-3 py-1 bg-red-500/20 text-red-500 rounded-full text-sm"
                >Tidak Aktif</span
              >
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <button
                  @click="
                    () => {
                      editData = user
                      formAddUser = user
                    }
                  "
                  class="p-2 text-slate-600 hover:text-primary-400 hover:bg-slate-50 rounded-lg"
                  title="Edit"
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
                  @click="handleDelete(user?.id)"
                  class="p-2 text-slate-600 hover:text-error-500 hover:bg-slate-50 rounded-lg"
                  title="Hapus"
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
      </div>
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 sm:px-6 py-4 border-t border-slate-200 gap-3">
        <p class="text-sm text-slate-600">
          Menampilkan data ke-{{ (page - 1) * 10 + 1 }} (Total: {{ totalData }})
        </p>
        <div class="flex flex-wrap gap-2">
          <button
            @click="page--"
            :disabled="page <= 1"
            class="px-3 sm:px-4 py-2 border border-slate-200 rounded-lg text-slate-600 disabled:opacity-30 text-sm"
          >
            Sebelumnya
          </button>
          <button
            v-for="p in lastPage"
            :key="p"
            @click="changePage(p)"
            :class="[
              page === p
                ? 'bg-primary-500 text-slate-800'
                : 'border border-slate-200 text-slate-600 hover:bg-slate-100',
            ]"
            class="px-3 sm:px-4 py-2 rounded-lg transition-colors text-sm hidden sm:inline-flex"
          >
            {{ p }}
          </button>

          <button
            @click="page++"
            :disabled="page >= lastPage"
            class="px-3 sm:px-4 py-2 border border-slate-200 rounded-lg text-slate-600 disabled:opacity-30 disabled:cursor-not-allowed text-sm"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </main>

  <div
    v-if="isAddUser"
    id="addUserModal"
    class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
  >
    <div
      class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold">Tambah Pengguna Baru</h2>
        <button
          @click="
            () => {
              isAddUser = false
              clearFormUser()
            }
          "
          class="p-2 text-slate-600 hover:text-slate-800"
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
      <form @submit.prevent="handleAddUser" class="p-4 sm:p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Nama*</label>
            <input
              v-model="formAddUser.name"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Email</label>
            <input
              v-model="formAddUser.email"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Password</label>
            <input
              v-model="formAddUser.password"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">NISN</label>
            <input
              v-model="formAddUser.nisn"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Role</label>
            <select
              v-model="formAddUser.role"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            >
              <option value="">Pilih role</option>
              <option value="student">Student</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            @click="
              () => {
                isAddUser = false
                clearFormUser()
              }
            "
            type="button"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-100"
          >
            Batal
          </button>
          <button
            type="submit"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-semibold text-white"
          >
            Simpan Pengguna
          </button>
        </div>
      </form>
    </div>
  </div>
  <div
    v-if="editData"
    id="editUserModal"
    class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
  >
    <div
      class="bg-white border border-slate-200 rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
    >
      <div class="flex items-center justify-between p-6 border-b border-slate-200">
        <h2 class="text-xl font-bold">Edit Pengguna</h2>
        <button
          @click="
            () => {
              editData = null
              clearFormUser()
            }
          "
          class="p-2 text-slate-600 hover:text-slate-800"
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
      <form @submit.prevent="handleEditUser" class="p-4 sm:p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="col-span-2">
            <label class="block text-sm text-slate-600 mb-2">Nama*</label>
            <input
              v-model="formAddUser.name"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Email</label>
            <input
              v-model="formAddUser.email"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-sm text-slate-600 mb-2">NISN</label>
            <input
              v-model="formAddUser.nisn"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            />
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Role</label>
            <select
              v-model="formAddUser.role"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            >
              <option value="">Pilih role</option>
              <option value="student">Student</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div>
            <label class="block text-sm text-slate-600 mb-2">Aktif</label>
            <select
              v-model="formAddUser.is_active"
              type="text"
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:border-primary-500 focus:outline-none"
            >
              <option :value="false">Tidak Aktif</option>
              <option :value="true">Aktif</option>
            </select>
          </div>
        </div>

        <div class="flex gap-4 pt-4">
          <button
            @click="
              () => {
                editData = null
                clearFormUser()
              }
            "
            type="button"
            class="flex-1 py-3 border border-slate-200 rounded-xl hover:bg-slate-100"
          >
            Batal
          </button>
          <button
            type="submit"
            class="flex-1 py-3 bg-primary-600 rounded-xl font-semibold text-white"
          >
            Simpan Pengguna
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
