<script setup>
import api from '@/lib/axios'
import { ref, onMounted, nextTick } from 'vue'
import { marked } from 'marked'

const messages = ref([
  {
    id: 1,
    sender: 'ai',
    text: 'Halo! Saya SmartLib AI Agent. Ada yang bisa saya bantu terkait buku, peminjaman, atau informasi perpustakaan hari ini?',
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  },
])
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
  if (!newMessage.value.trim()) return

  // Tambahkan pesan user
  messages.value.push({
    id: Date.now(),
    sender: 'user',
    text: newMessage.value,
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
  })

  scrollToBottom()

  // Simulasi AI mengetik
  isTyping.value = true

  try {
    const ress = await api.post('ai-student', {
      message: newMessage.value,
    })

    newMessage.value = ''

    console.log(ress, newMessage.value)

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
      text: error.response.data.message,
      time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    })
  } finally {
    isTyping.value = false
  }
}

// Auto resize textarea
const adjustTextareaHeight = (e) => {
  const el = e.target
  el.style.height = '40px'
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
  <div class="flex flex-col h-[calc(100dvh-72px)] bg-slate-50 font-sans relative overflow-hidden">
    <header
      class="bg-white/80 backdrop-blur-md px-4 py-3 flex items-center shadow-sm border-b border-slate-100 z-10"
    >
      <div
        class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center mr-3 shadow-md relative"
      >
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M13 10V3L4 14h7v7l9-11h-7z"
          />
        </svg>
        <span
          class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"
        ></span>
      </div>
      <div>
        <h1 class="text-base font-bold text-slate-800 leading-tight">SmartLib AI Agent</h1>
        <p class="text-xs text-green-500 font-medium flex items-center gap-1">
          <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block"></span>
          Online
        </p>
      </div>
      <button
        class="ml-auto p-2 text-slate-400 hover:text-slate-600 rounded-full hover:bg-slate-100 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
          />
        </svg>
      </button>
    </header>

    <div class="flex-1 overflow-y-auto p-4 space-y-5" ref="chatContainer">
      <div
        v-for="msg in messages"
        :key="msg.id"
        :class="['flex', msg.sender === 'user' ? 'justify-end' : 'justify-start']"
      >
        <div
          v-if="msg.sender === 'ai'"
          class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex-shrink-0 flex items-center justify-center mr-2 shadow-sm mt-auto"
        >
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 10V3L4 14h7v7l9-11h-7z"
            />
          </svg>
        </div>

        <div
          :class="[
            'max-w-[80%] px-4 py-3 shadow-sm relative group',
            msg.sender === 'user'
              ? 'bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-2xl rounded-tr-sm'
              : 'bg-white border border-slate-100 text-slate-700 rounded-2xl rounded-tl-sm',
          ]"
        >
          <div 
            class="markdown-content text-[14px] leading-relaxed" 
            v-html="parseMessage(msg.text, msg.sender)"
          ></div>
          <p
            :class="[
              'text-[10px] mt-1.5 flex items-center gap-1',
              msg.sender === 'user' ? 'text-primary-100 justify-end' : 'text-slate-400',
            ]"
          >
            {{ msg.time }}
            <svg
              v-if="msg.sender === 'user'"
              class="w-3 h-3 text-primary-200"
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
          </p>
        </div>
      </div>

      <div v-if="isTyping" class="flex justify-start">
        <div
          class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex-shrink-0 flex items-center justify-center mr-2 mt-auto"
        >
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 10V3L4 14h7v7l9-11h-7z"
            />
          </svg>
        </div>
        <div
          class="bg-white border border-slate-100 rounded-2xl rounded-tl-sm px-4 py-4 flex items-center space-x-1.5 shadow-sm"
        >
          <div
            class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"
            style="animation-delay: 0s"
          ></div>
          <div
            class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"
            style="animation-delay: 0.15s"
          ></div>
          <div
            class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"
            style="animation-delay: 0.3s"
          ></div>
        </div>
      </div>
    </div>

    <div
      class="p-3 bg-white border-t border-slate-100 shadow-[0_-4px_15px_-3px_rgba(0,0,0,0.05)] relative z-10"
    >
      <div
        class="flex items-end gap-2 bg-slate-50 p-1.5 rounded-[24px] border border-slate-200 focus-within:border-primary-400 focus-within:ring-2 focus-within:ring-primary-100 transition-all duration-300"
      >
        <button
          class="p-2.5 text-slate-400 hover:text-primary-500 hover:bg-white transition-colors rounded-full flex-shrink-0"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
            />
          </svg>
        </button>

        <textarea
          v-model="newMessage"
          @keydown.enter.prevent="sendMessage"
          @input="adjustTextareaHeight"
          rows="1"
          placeholder="Tanya AI tentang buku..."
          class="flex-1 max-h-32 bg-transparent border-none focus:ring-0 resize-none py-3 text-sm text-slate-700 placeholder-slate-400 scrollbar-hide leading-relaxed"
          style="min-height: 44px"
        ></textarea>

        <button
          @click="sendMessage"
          :disabled="!newMessage.trim()"
          :class="[
            'p-2.5 rounded-full transition-all duration-300 flex-shrink-0 mb-[2px] mr-[2px]',
            newMessage.trim()
              ? 'bg-primary-600 text-white hover:bg-primary-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5'
              : 'bg-slate-200 text-slate-400',
          ]"
        >
          <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Markdown styling */
.markdown-content :deep(p) {
  margin-bottom: 0.5rem;
}
.markdown-content :deep(p:last-child) {
  margin-bottom: 0;
}
.markdown-content :deep(strong) {
  font-weight: 700;
}
.markdown-content :deep(ul) {
  list-style-type: disc;
  margin-left: 1.2rem;
  margin-bottom: 0.5rem;
  padding-left: 0.5rem;
}
.markdown-content :deep(ol) {
  list-style-type: decimal;
  margin-left: 1.2rem;
  margin-bottom: 0.5rem;
  padding-left: 0.5rem;
}
.markdown-content :deep(li) {
  margin-bottom: 0.25rem;
}
.markdown-content :deep(a) {
  color: #3b82f6;
  text-decoration: underline;
}
</style>
