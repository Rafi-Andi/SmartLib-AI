<script setup>
import api from '@/lib/axios'
import { ref, onMounted, nextTick, watch } from 'vue'
import { marked } from 'marked'

const savedMessages = localStorage.getItem('admin_messages')
const initialMessages = savedMessages ? JSON.parse(savedMessages) : [
  {
    id: 1,
    sender: 'ai',
    text: 'Halo! Saya Admin Analyst LibSmart AI. Saya dapat membantu Anda memantau performa, menganalisis data, mengecek stok buku, serta mengelola tunggakan dan denda siswa. Ada yang bisa saya bantu hari ini?',
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  },
]

const messages = ref(initialMessages)


watch(messages, (newMessages) => {
  localStorage.setItem('admin_messages', JSON.stringify(newMessages))
}, { deep: true })

const newMessage = ref('')
const isTyping = ref(false)
const chatContainer = ref(null)

const scrollToBottom = async () => {
  await nextTick()
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight
  }
}

const sendMessage = async () => {
  if (!newMessage.value.trim() || isTyping.value) return

  messages.value.push({
    id: Date.now(),
    sender: 'user',
    text: newMessage.value,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  })

  scrollToBottom()

  isTyping.value = true

  try {
    const ress = await api.post('ai-admin', {
      message: newMessage.value,
    })

    newMessage.value = ''

    messages.value.push({
      id: Date.now(),
      sender: 'ai',
      text: ress.data.ai_response,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    })
    scrollToBottom()
  } catch (error) {
    messages.value.push({
      id: Date.now(),
      sender: 'ai',
      text: error.response?.data?.message || 'Terjadi kesalahan sistem.',
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    })
  } finally {
    isTyping.value = false
  }
}

const adjustTextareaHeight = (e) => {
  const el = e.target
  el.style.height = '48px'
  el.style.height = el.scrollHeight + 'px'
}

const parseMessage = (text, sender) => {
  if (!text) return ''
  if (sender === 'ai') {
    return marked.parse(text)
  }
  return text.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>')
}

onMounted(() => {
  scrollToBottom()
})
</script>

<template>
  <main class="flex-1 ml-64 h-screen flex flex-col bg-slate-50 relative overflow-hidden font-sans">
    <header
      class="bg-white/90 backdrop-blur-md px-8 py-5 flex items-center shadow-sm border-b border-slate-200 z-10"
    >
      <div
        class="w-12 h-12 bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl flex items-center justify-center mr-4 shadow-md relative"
      >
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
          />
        </svg>
        <span
          class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"
        ></span>
      </div>
      <div>
        <h1 class="text-xl font-bold text-slate-800 leading-tight">Admin Analyst AI</h1>
        <p class="text-sm text-green-500 font-medium flex items-center gap-1.5 mt-0.5">
          <span class="w-2 h-2 bg-green-500 rounded-full inline-block animate-pulse"></span>
          Sistem Online
        </p>
      </div>
    </header>

    <div class="flex-1 overflow-y-auto p-8 space-y-6 flex flex-col" ref="chatContainer">
      <div class="max-w-4xl w-full mx-auto space-y-6 flex flex-col flex-1">
        <div
          v-for="msg in messages"
          :key="msg.id"
          :class="['flex', msg.sender === 'user' ? 'justify-end' : 'justify-start']"
        >
          <div
            v-if="msg.sender === 'ai'"
            class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex-shrink-0 flex items-center justify-center mr-3 shadow-md mt-auto"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
              />
            </svg>
          </div>

          <div
            :class="[
              'max-w-[85%] px-6 py-4 shadow-sm relative group',
              msg.sender === 'user'
                ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-3xl rounded-tr-sm'
                : 'bg-white border border-slate-200 text-slate-700 rounded-3xl rounded-tl-sm',
            ]"
          >
            <div 
              class="markdown-content text-[15px] leading-relaxed" 
              v-html="parseMessage(msg.text, msg.sender)"
            ></div>
            <p
              :class="[
                'text-xs mt-2 flex items-center gap-1.5',
                msg.sender === 'user' ? 'text-primary-100 justify-end' : 'text-slate-400',
              ]"
            >
              {{ msg.time }}
              <svg
                v-if="msg.sender === 'user'"
                class="w-3.5 h-3.5 text-primary-200"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </p>
          </div>
        </div>

        <div v-if="isTyping" class="flex justify-start">
          <div
            class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex-shrink-0 flex items-center justify-center mr-3 mt-auto"
          >
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div
            class="bg-white border border-slate-200 rounded-3xl rounded-tl-sm px-6 py-5 flex items-center space-x-2 shadow-sm"
          >
            <div class="w-2.5 h-2.5 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0s"></div>
            <div class="w-2.5 h-2.5 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.15s"></div>
            <div class="w-2.5 h-2.5 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.3s"></div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="p-4 bg-white border-t border-slate-200 shadow-[0_-4px_15px_-3px_rgba(0,0,0,0.03)] relative z-10"
    >
      <div class="max-w-4xl mx-auto flex items-end gap-3 bg-slate-50 p-2 rounded-[28px] border border-slate-200 focus-within:border-primary-400 focus-within:ring-4 focus-within:ring-primary-100/50 transition-all duration-300">
        <textarea
          v-model="newMessage"
          @keydown.enter.prevent="sendMessage"
          @input="adjustTextareaHeight"
          rows="1"
          placeholder="Minta ringkasan analitik, cari tahu denda siswa, atau stok buku..."
          class="outline-none flex-1 max-h-48 bg-transparent border-none focus:ring-0 resize-none py-3.5 px-4 text-[15px] text-slate-700 placeholder-slate-400 scrollbar-hide leading-relaxed"
          style="min-height: 48px"
        ></textarea>

        <button
          @click="sendMessage"
          :disabled="!newMessage.trim() || isTyping"
          :class="[
            'p-3.5 rounded-full transition-all duration-300 flex-shrink-0 mb-1 mr-1',
            newMessage.trim() && !isTyping
              ? 'bg-slate-800 text-white hover:bg-slate-900 shadow-md hover:shadow-lg transform hover:-translate-y-0.5'
              : 'bg-slate-200 text-slate-400',
          ]"
        >
          <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </button>
      </div>
    </div>
  </main>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.markdown-content :deep(p) {
  margin-bottom: 0.75rem;
}
.markdown-content :deep(p:last-child) {
  margin-bottom: 0;
}
.markdown-content :deep(strong) {
  font-weight: 700;
  color: #1e293b;
}
.markdown-content :deep(ul) {
  list-style-type: disc;
  margin-left: 1.5rem;
  margin-bottom: 0.75rem;
  padding-left: 0.5rem;
}
.markdown-content :deep(ol) {
  list-style-type: decimal;
  margin-left: 1.5rem;
  margin-bottom: 0.75rem;
  padding-left: 0.5rem;
}
.markdown-content :deep(li) {
  margin-bottom: 0.35rem;
}
.markdown-content :deep(a) {
  color: #3b82f6;
  text-decoration: underline;
  transition: color 0.2s;
}
.markdown-content :deep(a:hover) {
  color: #2563eb;
}
.markdown-content :deep(h1), .markdown-content :deep(h2), .markdown-content :deep(h3) {
  font-weight: 700;
  margin-top: 1rem;
  margin-bottom: 0.5rem;
  color: #0f172a;
}
.markdown-content :deep(h1) { font-size: 1.5em; }
.markdown-content :deep(h2) { font-size: 1.25em; }
.markdown-content :deep(h3) { font-size: 1.1em; }
</style>