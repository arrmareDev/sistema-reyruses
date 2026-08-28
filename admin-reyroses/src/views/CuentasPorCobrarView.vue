<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row md:items-center justify-between gap-3 z-10', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Cuentas por Cobrar
        </h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          {{ pedidosStore.total }} venta{{ pedidosStore.total !== 1 ? 's' : '' }} a crédito sin cobrar
        </p>
      </div>
      <div
        :class="['px-4 py-2.5 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-amber-50 border-amber-100']">
        <span
          :class="['text-[10px] font-semibold uppercase tracking-wide block', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Total
          por cobrar</span>
        <span class="text-lg font-bold font-mono tabular-nums text-amber-500">S/ {{
          pedidosStore.totalPendiente.toFixed(2) }}</span>
      </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div v-if="pedidosStore.orders.length === 0" class="text-center py-20">
        <div
          :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
          <HandCoins :class="['w-8 h-8', isDarkTheme ? 'text-ink-600' : 'text-gray-400']" :stroke-width="1.5" />
        </div>
        <p :class="['text-base', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">No hay cuentas pendientes</p>
        <p :class="['text-sm mt-1', isDarkTheme ? 'text-ink-700' : 'text-gray-400']">Todas las ventas a crédito están
          al día</p>
      </div>

      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
              <tr
                :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                <th class="p-4 font-semibold">Cliente</th>
                <th class="p-4 font-semibold">Celular</th>
                <th class="p-4 font-semibold text-right">Monto</th>
                <th class="p-4 font-semibold">Fecha límite</th>
                <th class="p-4 font-semibold text-center">Estado</th>
                <th class="p-4 font-semibold text-center">Acción</th>
              </tr>
            </thead>
            <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
              <tr v-for="order in pedidosStore.orders" :key="order.id"
                :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
                <td class="p-4 font-medium">
                  {{ order.customer_name }}
                  <span
                    :class="['block text-xs font-mono tabular-nums', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Pedido
                    #{{
                      order.id.toString().padStart(4, '0') }}</span>
                </td>
                <td :class="['p-4', isDarkTheme ? 'text-ink-500' : 'text-gray-500']">{{ order.customer_phone }}</td>
                <td class="p-4 text-right font-bold font-mono tabular-nums text-pine-500">S/ {{ order.total_amount }}
                </td>
                <td class="p-4">{{ new Date(order.fecha_limite_pago).toLocaleDateString() }}</td>
                <td class="p-4 text-center">
                  <span :class="[
                    'px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide',
                    diasRestantes(order.fecha_limite_pago) < 0 ? 'bg-brick-500/20 text-brick-400' : 'bg-amber-500/20 text-amber-400',
                  ]">
                    {{ etiquetaVencimiento(order.fecha_limite_pago) }}
                  </span>
                </td>
                <td class="p-4 text-center">
                  <button @click="openConfirm(order)"
                    class="px-3 py-1.5 bg-pine-500 hover:bg-pine-600 text-white text-xs font-semibold rounded-lg transition-all inline-flex items-center gap-1.5">
                    <Check class="w-3.5 h-3.5" :stroke-width="2.5" />
                    Marcar pagado
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="px-4">
          <Pagination :current-page="pedidosStore.currentPage" :last-page="pedidosStore.lastPage"
            :from="pedidosStore.from" :to="pedidosStore.to" :total="pedidosStore.total"
            @change="(page) => pedidosStore.fetchOrders(page, filtro)" />
        </div>
      </div>
    </div>

    <ConfirmModal v-model="showConfirm" title="Confirmar pago" :message="confirmMessage"
      confirm-label="Sí, marcar como pagado" cancel-label="Cancelar" @confirm="executeMarcarPagado"
      @cancel="showConfirm = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { HandCoins, Check } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { usePedidosStore } from '@/stores/pedidos'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'

const { isDarkTheme } = useTheme()
const pedidosStore = usePedidosStore()

const filtro = { tipo_pago: 'Credito', estado_pago: 'Pendiente' }

const diasRestantes = (fecha) => {
  const hoy = new Date(new Date().toDateString())
  const limite = new Date(fecha)
  return Math.round((limite - hoy) / (1000 * 60 * 60 * 24))
}

const etiquetaVencimiento = (fecha) => {
  const dias = diasRestantes(fecha)
  if (dias < 0) return `Vencido hace ${Math.abs(dias)}d`
  if (dias === 0) return 'Vence hoy'
  return `Vence en ${dias}d`
}

const showConfirm = ref(false)
const pendingOrder = ref(null)

const confirmMessage = computed(() => {
  if (!pendingOrder.value) return ''
  const o = pendingOrder.value
  return `Vas a registrar el pago de S/ ${o.total_amount} de ${o.customer_name} (Pedido #${o.id.toString().padStart(4, '0')}). Esto genera un ingreso de caja y no se puede deshacer.`
})

const openConfirm = (order) => {
  pendingOrder.value = order
  showConfirm.value = true
}

const executeMarcarPagado = async () => {
  if (pendingOrder.value) {
    await pedidosStore.marcarPagado(pendingOrder.value.id)
  }
  showConfirm.value = false
  pendingOrder.value = null
}

onMounted(() => {
  pedidosStore.fetchOrders(1, filtro)
})
</script>
