<template>
  <div
    :class="['rounded-2xl border transition-all group overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700 hover:border-ink-600' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow-lg']">
    <div :class="['relative h-40 overflow-hidden', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
      <img v-if="product.image_path" :src="apiUrl + '/storage/' + product.image_path"
        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
      <div v-else class="w-full h-full flex items-center justify-center">
        <ImageOff :class="['w-12 h-12', isDarkTheme ? 'text-ink-700' : 'text-gray-300']" :stroke-width="1.5" />
      </div>
      <span :class="[
        'absolute top-3 right-3 text-[10px] font-bold font-mono tabular-nums px-2.5 py-1 rounded-full backdrop-blur-sm',
        product.stock > 5
          ? 'bg-pine-500/20 text-pine-400 border border-pine-500/30'
          : product.stock > 0
            ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30'
            : 'bg-brick-500/20 text-brick-400 border border-brick-500/30',
      ]">
        Stock: {{ product.stock }}
      </span>
    </div>

    <div class="p-4">
      <div class="flex items-start justify-between gap-2 mb-2">
        <div>
          <h3
            :class="['font-semibold text-base leading-tight line-clamp-1', isDarkTheme ? 'text-white' : 'text-gray-900']">
            {{ product.name }}
          </h3>
          <p
            :class="['text-[11px] font-medium uppercase tracking-wider mt-1 flex items-center gap-1.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
            {{ product.category ? product.category.name : 'Sin categoría' }}
            <span
              :class="['px-1.5 py-0.5 rounded text-[9px] font-bold', product.tipo === 'Exportacion' ? 'bg-blue-500/20 text-blue-400' : 'bg-brass-500/20 text-brass-400']">
              {{ product.tipo === 'Exportacion' ? 'Exportación' : 'Nacional' }}
            </span>
          </p>
        </div>
      </div>

      <p :class="['text-xs line-clamp-2 mb-4 min-h-[32px]', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
        {{ product.description || 'Sin descripción detallada.' }}
      </p>

      <div
        :class="['flex items-end justify-between pt-3 border-t', isDarkTheme ? 'border-ink-700' : 'border-gray-100']">
        <div>
          <span
            :class="['text-[10px] font-medium uppercase', isDarkTheme ? 'text-ink-600' : 'text-gray-400']">Desde</span>
          <p class="text-xl font-bold font-mono tabular-nums text-pine-500">S/ {{ product.price_50 || '0.00' }}</p>
        </div>
        <div class="flex gap-2">
          <button @click="$emit('edit', product)"
            :class="['p-2 rounded-lg transition-all', isDarkTheme ? 'bg-ink-800 text-ink-600 hover:bg-ink-700 hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
            title="Editar">
            <Pencil class="w-4 h-4" :stroke-width="2" />
          </button>
          <button @click="$emit('delete', product)"
            class="p-2 bg-brick-500/10 text-brick-400 rounded-lg hover:bg-brick-500 hover:text-white transition-all"
            title="Eliminar">
            <Trash2 class="w-4 h-4" :stroke-width="2" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ImageOff, Pencil, Trash2 } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()
const apiUrl = import.meta.env.VITE_API_URL

defineProps({
  product: { type: Object, required: true },
})

defineEmits(['edit', 'delete'])
</script>
