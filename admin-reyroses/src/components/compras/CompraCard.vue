<template>
  <button @click="$emit('click')"
    :class="['w-full text-left rounded-2xl border p-5 transition-all', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32] hover:border-[#3a3a42]' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow-lg']">
    <div class="flex justify-between items-start mb-3">
      <div>
        <p :class="['font-bold text-sm', isDarkTheme ? 'text-white' : 'text-gray-900']">
          Compra #{{ compra.id.toString().padStart(4, '0') }}
        </p>
        <p :class="['text-xs mt-0.5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          {{ new Date(compra.fecha).toLocaleDateString() }} · {{ compra.proveedor || 'Sin proveedor' }}
        </p>
      </div>
      <span :class="[
        'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide',
        compra.estado === 'Recibida' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400',
      ]">
        {{ compra.estado }}
      </span>
    </div>

    <div :class="['flex items-center gap-1.5 text-xs mb-4', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
      <Cigarette class="w-3.5 h-3.5" :stroke-width="2" />
      {{ compra.tabacos.length }} tabaco{{ compra.tabacos.length !== 1 ? 's' : '' }}
    </div>

    <div :class="['grid grid-cols-2 gap-3 pt-3 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-100']">
      <div>
        <span :class="['text-[10px] font-medium uppercase', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">Costo
          total</span>
        <p :class="['text-sm font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">S/ {{
          compra.costo_total.toFixed(2) }}</p>
      </div>
      <div>
        <span :class="['text-[10px] font-medium uppercase', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">Ganancia
          est.</span>
        <p :class="['text-sm font-bold', compra.ganancia_estimada >= 0 ? 'text-emerald-500' : 'text-red-500']">
          S/ {{ compra.ganancia_estimada.toFixed(2) }}
        </p>
      </div>
    </div>
  </button>
</template>

<script setup>
import { Cigarette } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  compra: { type: Object, required: true },
})

defineEmits(['click'])
</script>
