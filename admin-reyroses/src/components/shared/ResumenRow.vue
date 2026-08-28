<template>
  <div class="flex justify-between text-sm">
    <span :class="isDarkTheme ? 'text-zinc-400' : 'text-gray-600'">{{ label }}</span>
    <span
      :class="['font-mono', bold ? 'font-bold' : 'font-medium', valueClass || (isDarkTheme ? 'text-white' : 'text-gray-900')]">
      {{ value }}
    </span>
  </div>
</template>

<script setup>
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  label: { type: String, required: true },
  value: { type: String, required: true },
  bold: { type: Boolean, default: false },
  valueClass: { type: String, default: '' },
})
</script>
