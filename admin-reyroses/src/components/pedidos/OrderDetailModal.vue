<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div v-if="order"
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="['flex justify-between items-center pb-4 mb-4 shrink-0 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-lg md:text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
            Pedido #{{ order.id.toString().padStart(4, '0') }}
          </h2>
          <button @click="$emit('update:modelValue', false)"
            :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <div
          :class="['mb-6 p-4 rounded-xl text-sm shrink-0 border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Cliente:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_name
              }}</span>
          </p>
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Celular:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_phone
              }}</span>
          </p>
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Direccion:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_address
              }}</span>
          </p>
          <p class="flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Pago:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">
              {{ order.tipo_pago || 'Contado' }}
              <span v-if="order.tipo_pago === 'Credito'"
                :class="order.estado_pago === 'Pagado' ? 'text-emerald-500' : 'text-amber-500'">
                ({{ order.estado_pago }}{{ order.fecha_limite_pago ? ' hasta ' + new
                  Date(order.fecha_limite_pago).toLocaleDateString() : '' }})
              </span>
            </span>
          </p>
        </div>

        <h3
          :class="['text-xs font-semibold uppercase tracking-widest mb-3 shrink-0', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          Rosas Solicitadas
        </h3>
        <ul class="space-y-3 mb-6 overflow-y-auto pr-2 flex-grow">
          <li v-for="item in order.items" :key="item.id"
            :class="['flex flex-col sm:flex-row justify-between sm:items-center p-3 rounded-xl gap-2 border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
            <div>
              <p :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ item.quantity }}x {{
                item.product_name }}</p>
              <p :class="['text-xs', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Precio unitario: S/ {{ item.price
                }}</p>
            </div>
            <p class="font-bold text-emerald-500">S/ {{ (item.price * item.quantity).toFixed(2) }}</p>
          </li>
        </ul>

        <div
          :class="['flex justify-between items-center pt-4 shrink-0 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <span :class="['font-semibold', isDarkTheme ? 'text-zinc-400' : 'text-gray-500']">Total a Cobrar:</span>
          <span class="text-xl md:text-2xl font-bold text-emerald-500">S/ {{ order.total_amount }}</span>
        </div>

        <button v-if="order.tipo_pago === 'Credito' && order.estado_pago === 'Pendiente'"
          @click="$emit('marcar-pagado', order.id)"
          class="w-full mt-4 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
          Marcar crédito como pagado
        </button>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { X } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'

const { isDarkTheme } = useTheme()

defineProps({
  modelValue: { type: Boolean, default: false },
  order: { type: Object, default: null },
})

defineEmits(['update:modelValue', 'marcar-pagado'])
</script>
