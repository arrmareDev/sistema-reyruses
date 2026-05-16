<template>
  <Toaster position="top-right" richColors />
  
  <HomeView />
</template>

<script setup>
import HomeView from '@/views/HomeView.vue'
import { Toaster } from 'vue-sonner' // <-- Importamos la herramienta
</script>