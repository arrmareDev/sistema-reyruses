<template>
  <div class="min-h-screen bg-neutral-900 font-sans text-gray-100">
    <Toaster position="top-right" richColors />

    <router-view />
  </div>
</template>

<script setup>
// Importamos la herramienta de notificaciones
import { Toaster } from 'vue-sonner'
</script>
