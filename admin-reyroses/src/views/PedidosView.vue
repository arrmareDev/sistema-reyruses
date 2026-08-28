<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Pedidos</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          {{ filteredOrders.length }} en vista actual
        </p>
      </div>
      <div class="flex gap-2">
        <button @click="pedidosStore.fetchOrders"
          :class="['p-2.5 rounded-xl transition-all flex items-center gap-2', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']">
          <RefreshCw class="w-5 h-5" :stroke-width="2" />
          <span class="hidden md:inline font-medium text-sm">Actualizar</span>
        </button>
        <button @click="showCrearPedido = true"
          class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
          <Plus class="w-4 h-4" :stroke-width="2" />
          Nuevo pedido
        </button>
      </div>
    </header>

    <div
      :class="['px-4 md:px-8 pt-4 flex gap-1 overflow-x-auto', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
      <button v-for="statusTab in ['Todos', 'Pendiente', 'Pagado', 'Cancelado']" :key="statusTab"
        @click="activeOrderTab = statusTab" :class="[
          'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg',
          activeOrderTab === statusTab
            ? isDarkTheme ? 'text-emerald-400 bg-[#0f0f12]' : 'text-emerald-600 bg-gray-100'
            : isDarkTheme ? 'text-zinc-500 hover:text-zinc-300 hover:bg-[#1e1e24]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
        ]">
        {{ statusTab }}
      </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32]' : 'bg-white border-gray-200']">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[850px]">
            <thead>
              <tr
                :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-[#1e1e24] text-zinc-500 border-b border-[#2a2a32]' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                <th class="p-4 md:p-5 font-semibold">N Pedido</th>
                <th class="p-4 md:p-5 font-semibold">Cliente</th>
                <th class="p-4 md:p-5 font-semibold">Celular</th>
                <th class="p-4 md:p-5 font-semibold">Fecha</th>
                <th class="p-4 md:p-5 font-semibold text-center">Pago</th>
                <th class="p-4 md:p-5 font-semibold text-center">Total</th>
                <th class="p-4 md:p-5 font-semibold text-center">Estado</th>
                <th class="p-4 md:p-5 font-semibold text-center">Accion</th>
              </tr>
            </thead>
            <tbody :class="['text-sm', isDarkTheme ? 'text-zinc-300' : 'text-gray-700']">
              <tr v-for="order in filteredOrders" :key="order.id"
                :class="['transition-colors', isDarkTheme ? 'border-b border-[#2a2a32] hover:bg-[#1e1e24]' : 'border-b border-gray-100 hover:bg-gray-50']">
                <td :class="['p-4 md:p-5 font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
                  #{{ order.id.toString().padStart(4, '0') }}
                </td>
                <td class="p-4 md:p-5 font-medium">{{ order.customer_name }}</td>
                <td :class="['p-4 md:p-5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">{{ order.customer_phone }}
                </td>
                <td :class="['p-4 md:p-5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                  {{ new Date(order.created_at).toLocaleDateString() }}
                </td>
                <td class="p-4 md:p-5 text-center">
                  <span v-if="order.tipo_pago === 'Credito'"
                    :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase', order.estado_pago === 'Pagado' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amber-500/20 text-amber-400']">
                    Crédito · {{ order.estado_pago }}
                  </span>
                  <span v-else
                    :class="['text-[10px] font-bold uppercase', isDarkTheme ? 'text-zinc-500' : 'text-gray-400']">Contado</span>
                </td>
                <td class="p-4 md:p-5 text-center text-emerald-500 font-bold">
                  S/ {{ order.total_amount }}
                </td>
                <td class="p-4 md:p-5 text-center">
                  <span :class="[
                    'px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide inline-flex items-center gap-1.5',
                    order.status === 'Pendiente'
                      ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30'
                      : order.status === 'Pagado'
                        ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                        : 'bg-red-500/20 text-red-400 border border-red-500/30',
                  ]">
                    <span class="w-1.5 h-1.5 rounded-full" :class="[
                      order.status === 'Pendiente' ? 'bg-amber-400' :
                        order.status === 'Pagado' ? 'bg-emerald-400' : 'bg-red-400'
                    ]"></span>
                    {{ order.status }}
                  </span>
                </td>
                <td class="p-4 md:p-5 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button @click="openOrderDetails(order)"
                      :class="['p-2 rounded-lg transition-colors', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
                      title="Ver Detalle">
                      <Eye class="w-4 h-4" :stroke-width="2" />
                    </button>
                    <template v-if="order.status === 'Pendiente'">
                      <button @click="openStatusConfirm(order.id, 'Pagado')"
                        class="p-2 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-lg transition-colors"
                        title="Marcar como Pagado">
                        <Check class="w-4 h-4" :stroke-width="2.5" />
                      </button>
                      <button @click="openStatusConfirm(order.id, 'Cancelado')"
                        class="p-2 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-colors"
                        title="Cancelar Pedido">
                        <X class="w-4 h-4" :stroke-width="2" />
                      </button>
                    </template>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredOrders.length === 0">
                <td colspan="8"
                  :class="['p-10 text-center font-medium', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                  No hay pedidos con el estado "{{ activeOrderTab }}".
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <OrderDetailModal v-model="showOrderModal" :order="selectedOrder" @marcar-pagado="handleMarcarPagado" />

    <CrearPedidoModal v-model="showCrearPedido" />

    <ConfirmModal v-model="showStatusConfirm" title="Confirmar accion"
      :message="`Vas a marcar este pedido como ${pendingStatusUpdate.status}. Esta accion no se puede deshacer.`"
      confirm-label="Confirmar" :danger-variant="pendingStatusUpdate.status === 'Cancelado'"
      @confirm="executeStatusUpdate" @cancel="showStatusConfirm = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { RefreshCw, Eye, Check, X, Plus } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { usePedidosStore } from '@/stores/pedidos'
import OrderDetailModal from '@/components/pedidos/OrderDetailModal.vue'
import CrearPedidoModal from '@/components/pedidos/CrearPedidoModal.vue'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'

const { isDarkTheme } = useTheme()
const pedidosStore = usePedidosStore()

const activeOrderTab = ref('Todos')

const showOrderModal = ref(false)
const selectedOrder = ref(null)
const showCrearPedido = ref(false)

const showStatusConfirm = ref(false)
const pendingStatusUpdate = ref({ id: null, status: '' })

const filteredOrders = computed(() => {
  if (activeOrderTab.value === 'Todos') return pedidosStore.orders
  return pedidosStore.orders.filter((o) => o.status === activeOrderTab.value)
})

const openOrderDetails = (order) => {
  selectedOrder.value = order
  showOrderModal.value = true
}

const openStatusConfirm = (id, status) => {
  pendingStatusUpdate.value = { id, status }
  showStatusConfirm.value = true
}

const executeStatusUpdate = async () => {
  const { id, status } = pendingStatusUpdate.value
  await pedidosStore.updateStatus(id, status)
  showStatusConfirm.value = false
}

const handleMarcarPagado = async (id) => {
  await pedidosStore.marcarPagado(id)
  showOrderModal.value = false
}

onMounted(() => {
  pedidosStore.fetchOrders()
})
</script>
