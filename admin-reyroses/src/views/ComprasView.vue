<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Compras</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          {{ comprasStore.total }} pedido{{ comprasStore.total !== 1 ? 's' : '' }} de compra registrados
        </p>
      </div>
      <button @click="showForm = true"
        class="bg-brass-500 hover:bg-brass-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
        <Plus class="w-4 h-4" :stroke-width="2" />
        Nueva compra
      </button>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div v-if="comprasStore.compras.length === 0" class="text-center py-20">
        <div
          :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
          <Truck :class="['w-8 h-8', isDarkTheme ? 'text-ink-600' : 'text-gray-400']" :stroke-width="1.5" />
        </div>
        <p :class="['text-base', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Aún no hay compras registradas</p>
        <p :class="['text-sm mt-1', isDarkTheme ? 'text-ink-700' : 'text-gray-400']">Registra tu primer pedido de
          tabacos</p>
      </div>

      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[820px]">
            <thead>
              <tr
                :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                <th class="p-4 font-semibold">Compra</th>
                <th class="p-4 font-semibold">Fecha</th>
                <th class="p-4 font-semibold">Proveedor</th>
                <th class="p-4 font-semibold text-center">Tabacos</th>
                <th class="p-4 font-semibold text-right">Costo Total</th>
                <th class="p-4 font-semibold text-right">Ganancia Est.</th>
                <th class="p-4 font-semibold text-center">Estado</th>
                <th class="p-4 font-semibold text-center">Acción</th>
              </tr>
            </thead>
            <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
              <tr v-for="compra in comprasStore.compras" :key="compra.id"
                :class="['transition-colors cursor-pointer', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']"
                @click="openDetail(compra)">
                <td :class="['p-4 font-bold font-mono tabular-nums', isDarkTheme ? 'text-white' : 'text-gray-900']">
                  #{{ compra.id.toString().padStart(4, '0') }}
                </td>
                <td :class="['p-4', isDarkTheme ? 'text-ink-500' : 'text-gray-500']">{{ new
                  Date(compra.fecha).toLocaleDateString() }}</td>
                <td class="p-4 font-medium">{{ compra.proveedor || '—' }}</td>
                <td class="p-4 text-center font-mono tabular-nums">{{ compra.tabacos.length }}</td>
                <td
                  :class="['p-4 text-right font-bold font-mono tabular-nums', isDarkTheme ? 'text-white' : 'text-gray-900']">
                  S/ {{ compra.costo_total.toFixed(2) }}
                </td>
                <td
                  :class="['p-4 text-right font-bold font-mono tabular-nums', compra.ganancia_estimada >= 0 ? 'text-pine-500' : 'text-brick-500']">
                  S/ {{ compra.ganancia_estimada.toFixed(2) }}
                </td>
                <td class="p-4 text-center">
                  <span
                    :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide', compra.estado === 'Recibida' ? 'bg-pine-500/20 text-pine-400' : 'bg-amber-500/20 text-amber-400']">
                    {{ compra.estado }}
                  </span>
                </td>
                <td class="p-4 text-center">
                  <button @click.stop="openDetail(compra)"
                    :class="['p-2 rounded-lg transition-colors', isDarkTheme ? 'bg-ink-800 text-ink-500 hover:bg-ink-700 hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
                    title="Ver detalle">
                    <Eye class="w-4 h-4" :stroke-width="2" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="px-4">
          <Pagination :current-page="comprasStore.currentPage" :last-page="comprasStore.lastPage"
            :from="comprasStore.from" :to="comprasStore.to" :total="comprasStore.total"
            @change="comprasStore.fetchCompras" />
        </div>
      </div>
    </div>

    <CompraForm v-model="showForm" />

    <CompraDetailModal v-model="showDetail" :compra="selectedCompra" @recibir="openRecibirConfirm" />

    <ConfirmModal v-model="showRecibirConfirm" title="Recibir compra"
      message="Esto aumentará el stock del catálogo según el desglose de tallos de cada tabaco. No se puede deshacer."
      confirm-label="Sí, recibir" @confirm="executeRecibir" @cancel="showRecibirConfirm = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Plus, Truck, Eye } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useComprasStore } from '@/stores/compras'
import CompraForm from '@/components/compras/CompraForm.vue'
import CompraDetailModal from '@/components/compras/CompraDetailModal.vue'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'
import Pagination from '@/components/shared/Pagination.vue'

const { isDarkTheme } = useTheme()
const comprasStore = useComprasStore()

const showForm = ref(false)
const showDetail = ref(false)
const selectedCompra = ref(null)

const showRecibirConfirm = ref(false)
const pendingRecibirId = ref(null)

const openDetail = (compra) => {
  selectedCompra.value = compra
  showDetail.value = true
}

const openRecibirConfirm = (id) => {
  pendingRecibirId.value = id
  showRecibirConfirm.value = true
}

const executeRecibir = async () => {
  await comprasStore.recibirCompra(pendingRecibirId.value)
  showRecibirConfirm.value = false
  showDetail.value = false
}

onMounted(() => {
  comprasStore.fetchCompras()
})
</script>
