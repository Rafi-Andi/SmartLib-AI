import AdminBooks from '@/pages/Admin/AdminBooks.vue'
import AdminChatbot from '@/pages/Admin/AdminChatbot.vue'
import AdminFines from '@/pages/Admin/AdminFines.vue'
import AdminIndex from '@/pages/Admin/AdminIndex.vue'
import AdminKiosk from '@/pages/Admin/AdminKiosk.vue'
import AdminLayout from '@/pages/Admin/AdminLayout.vue'
import AdminLoginKiosk from '@/pages/Admin/AdminLoginKiosk.vue'
import AdminRfid from '@/pages/Admin/AdminRfid.vue'
import AdminTransactions from '@/pages/Admin/AdminTransactions.vue'
import AdminUsers from '@/pages/Admin/AdminUsers.vue'
import Login from '@/pages/Auth/Login.vue'
import RegisterSchool from '@/pages/Auth/RegisterSchool.vue'
import RegisterStudent from '@/pages/Auth/RegisterStudent.vue'
import StudentBorrow from '@/pages/Student/StudentBorrow.vue'
import StudentChatbot from '@/pages/Student/StudentChatbot.vue'
import StudentIndex from '@/pages/Student/StudentIndex.vue'
import StudentLayout from '@/pages/Student/StudentLayout.vue'
import StudentProfile from '@/pages/Student/StudentProfile.vue'
import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login,
    },
    {
      path: '/register-school',
      component: RegisterSchool,
      name: 'RegisterSchool',
    },
    {
      path: '/register-student',
      component: RegisterStudent,
      name: 'RegisterStudent',
    },
    {
      path: '/login_kiosk',
      component: AdminLoginKiosk,
      name: 'AdminLoginKiosk',
    },
    {
      path: '/index_kiosk',
      component: AdminKiosk,
      name: 'AdminKiosk',
    },
    {
      path: '/student',
      component: StudentLayout,
      name: 'StudentLayout',
      redirect: 'student/index',
      children: [
        {
          path: 'index',
          component: StudentIndex,
          name: 'StudentIndex',
        },
        {
          path: 'borrow',
          component: StudentBorrow,
          name: 'StudentBorrow',
        },
        {
          path: 'chatbot',
          component: StudentChatbot,
          name: 'StudentChatbot',
        },
        {
          path: 'profile',
          component: StudentProfile,
          name: 'StudentProfile',
        },
      ],
    },
    {
      path: '/admin',
      component: AdminLayout,
      name: 'AdminLayout',
      redirect: '/admin/index',
      children: [
        {
          path: 'index',
          component: AdminIndex,
          name: 'AdminIndex',
        },
        {
          path: 'books',
          component: AdminBooks,
          name: 'AdminBooks',
        },
        {
          path: 'users',
          component: AdminUsers,
          name: 'AdminUsers',
        },
        {
          path: 'transactions',
          component: AdminTransactions,
          name: 'AdminTransactions',
        },
        {
          path: 'fines',
          component: AdminFines,
          name: 'AdminFines',
        },
        {
          path: 'rfid',
          component: AdminRfid,
          name: 'AdminRfid',
        },
        {
          path: 'chatbot',
          component: AdminChatbot,
          name: 'AdminChatbot',
        },
      ],
    },
  ],
})

export default router
