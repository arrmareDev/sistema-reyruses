<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div v-if="order"
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex justify-between items-center pb-4 mb-4 shrink-0 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <h2
            :class="['text-lg md:text-xl font-bold font-mono tabular-nums', isDarkTheme ? 'text-white' : 'text-gray-900']">
            Pedido #{{ order.id.toString().padStart(4, '0') }}
          </h2>
          <button @click="$emit('update:modelValue', false)"
            :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white hover:bg-ink-800' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <div
          :class="['mb-6 p-4 rounded-xl text-sm shrink-0 border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Cliente:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_name
            }}</span>
          </p>
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Celular:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_phone
            }}</span>
          </p>
          <p class="mb-2 flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Direccion:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ order.customer_address
            }}</span>
          </p>
          <p class="flex items-center">
            <span
              :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Pago:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">
              {{ order.tipo_pago || 'Contado' }}
              <span v-if="order.tipo_pago === 'Credito'"
                :class="order.estado_pago === 'Pagado' ? 'text-pine-500' : 'text-amber-500'">
                ({{ order.estado_pago }}{{ order.fecha_limite_pago ? ' hasta ' + new
                  Date(order.fecha_limite_pago).toLocaleDateString() : '' }})
              </span>
            </span>
          </p>
        </div>

        <!-- Voucher de pago -->
        <div class="mb-6 shrink-0">
          <h3
            :class="['text-xs font-semibold uppercase tracking-widest mb-2', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
            Voucher de pago
          </h3>
          <a v-if="order.voucher_path" :href="voucherUrl(order.voucher_path)" target="_blank"
            :class="['flex items-center gap-3 p-3 rounded-xl border transition-colors', isDarkTheme ? 'bg-ink-800 border-ink-700 hover:border-ink-600' : 'bg-gray-50 border-gray-200 hover:border-gray-300']">
            <FileCheck class="w-5 h-5 text-pine-500 shrink-0" :stroke-width="2" />
            <span :class="['text-sm font-medium flex-1', isDarkTheme ? 'text-white' : 'text-gray-900']">Ver comprobante
              adjunto</span>
            <ExternalLink :class="['w-4 h-4', isDarkTheme ? 'text-ink-500' : 'text-gray-400']" :stroke-width="2" />
          </a>
          <label v-else
            :class="['flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-colors', isDarkTheme ? 'bg-ink-800 border-ink-700 hover:border-ink-600' : 'bg-gray-50 border-gray-200 hover:border-gray-300']">
            <Paperclip :class="['w-4 h-4 shrink-0', uploadingVoucher ? 'text-ink-500' : 'text-brass-500']"
              :stroke-width="2" />
            <span :class="['text-sm font-medium', isDarkTheme ? 'text-ink-500' : 'text-gray-500']">
              {{ uploadingVoucher ? 'Subiendo...' : 'Sin voucher — adjuntar ahora' }}
            </span>
            <input type="file" accept="image/*,application/pdf" class="hidden" :disabled="uploadingVoucher"
              @change="handleVoucherUpload" />
          </label>
        </div>

        <h3
          :class="['text-xs font-semibold uppercase tracking-widest mb-3 shrink-0', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          Rosas Solicitadas
        </h3>
        <ul class="space-y-3 mb-6 overflow-y-auto pr-2 flex-grow">
          <li v-for="item in order.items" :key="item.id"
            :class="['flex flex-col sm:flex-row justify-between sm:items-center p-3 rounded-xl gap-2 border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
            <div>
              <p :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ item.quantity }}x {{
                item.product_name }}</p>
              <p :class="['text-xs font-mono tabular-nums', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Precio
                unitario: S/ {{ item.price
                }}</p>
            </div>
            <p class="font-bold font-mono tabular-nums text-pine-500">S/ {{ (item.price * item.quantity).toFixed(2) }}
            </p>
          </li>
        </ul>

        <div
          :class="['flex justify-between items-center pt-4 shrink-0 border-t', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <span :class="['font-semibold', isDarkTheme ? 'text-ink-400' : 'text-gray-500']">Total a Cobrar:</span>
          <span class="text-xl md:text-2xl font-bold font-mono tabular-nums text-pine-500">S/ {{ order.total_amount
            }}</span>
        </div>

        <button v-if="order.tipo_pago === 'Credito' && order.estado_pago === 'Pendiente'"
          @click="$emit('marcar-pagado', order.id)"
          class="w-full mt-4 py-3 bg-brass-500 hover:bg-brass-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all">
          Marcar crédito como pagado
        </button>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { X, FileCheck, ExternalLink, Paperclip } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { usePedidosStore } from '@/stores/pedidos'

const { isDarkTheme } = useTheme()
const pedidosStore = usePedidosStore()
const apiUrl = import.meta.env.VITE_API_URL

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  order: { type: Object, default: null },
})

defineEmits(['update:modelValue', 'marcar-pagado'])

const uploadingVoucher = ref(false)
const voucherUrl = (path) => `${apiUrl}/storage/${path}`

const handleVoucherUpload = async (event) => {
  const file = event.target.files[0]
  if (!file || !props.order) return

  uploadingVoucher.value = true
  const ok = await pedidosStore.subirVoucher(props.order.id, file)
  uploadingVoucher.value = false

  // Refleja el cambio de una vez en el modal abierto, sin esperar a cerrarlo
  if (ok) {
    const actualizado = pedidosStore.orders.find((o) => o.id === props.order.id)
    if (actualizado) props.order.voucher_path = actualizado.voucher_path
  }
}
</script>
