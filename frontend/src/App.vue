<script setup>
import 'vue-sonner/style.css'
import { Toaster } from 'vue-sonner'
</script>

<template>
  <router-view></router-view>
  <Toaster position="top-right" richColors />
</template>

<style scoped></style>
