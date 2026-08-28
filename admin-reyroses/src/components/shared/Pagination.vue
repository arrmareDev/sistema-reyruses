<template>
  <div v-if="lastPage > 1" class="flex flex-col sm:flex-row items-center justify-between gap-3 px-2 py-4">
    <p :class="['text-xs font-mono tabular-nums', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
      Mostrando {{ from }}–{{ to }} de {{ total }}
    </p>

    <div class="flex items-center gap-2">
      <button @click="$emit('change', currentPage - 1)" :disabled="currentPage === 1"
        :class="['p-2 rounded-lg transition-colors disabled:opacity-30 disabled:pointer-events-none', isDarkTheme ? 'bg-ink-800 text-ink-500 hover:bg-ink-700 hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']">
        <ChevronLeft class="w-4 h-4" :stroke-width="2.5" />
      </button>

      <span
        :class="['text-xs font-semibold font-mono tabular-nums px-2 min-w-[90px] text-center', isDarkTheme ? 'text-ink-400' : 'text-gray-600']">
        Página {{ currentPage }} de {{ lastPage }}
      </span>

      <button @click="$emit('change', currentPage + 1)" :disabled="currentPage === lastPage"
        :class="['p-2 rounded-lg transition-colors disabled:opacity-30 disabled:pointer-events-none', isDarkTheme ? 'bg-ink-800 text-ink-500 hover:bg-ink-700 hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']">
        <ChevronRight class="w-4 h-4" :stroke-width="2.5" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  currentPage: { type: Number, required: true },
  lastPage: { type: Number, required: true },
  from: { type: Number, default: 0 },
  to: { type: Number, default: 0 },
  total: { type: Number, default: 0 },
})

defineEmits(['change'])
</script>
