<template>
  <Teleport to="body">
    <div v-if="modelValue"
      class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
      @click.self="$emit('cancel')">
      <div
        :class="['w-full max-w-sm rounded-2xl shadow-2xl p-6', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div class="flex items-center gap-3 mb-4">
          <div :class="['p-2.5 rounded-xl', dangerVariant ? 'bg-red-500/10' : 'bg-emerald-500/10']">
            <TriangleAlert :class="['w-5 h-5', dangerVariant ? 'text-red-400' : 'text-emerald-400']"
              :stroke-width="2" />
          </div>
          <h3 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ title }}</h3>
        </div>

        <p :class="['text-sm mb-6', isDarkTheme ? 'text-zinc-400' : 'text-gray-600']">{{ message }}</p>

        <div class="flex gap-3">
          <button @click="$emit('cancel')"
            :class="['flex-1 py-2.5 rounded-xl font-semibold text-sm transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-300 hover:bg-[#2a2a32]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200']">
            {{ cancelLabel }}
          </button>
          <button @click="$emit('confirm')"
            :class="['flex-1 py-2.5 rounded-xl font-semibold text-sm text-white transition-all', dangerVariant ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700']">
            {{ confirmLabel }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { TriangleAlert } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: '¿Estás seguro?' },
  message: { type: String, default: 'Esta acción no se puede deshacer.' },
  confirmLabel: { type: String, default: 'Confirmar' },
  cancelLabel: { type: String, default: 'Cancelar' },
  dangerVariant: { type: Boolean, default: false },
})

defineEmits(['confirm', 'cancel'])
</script>
