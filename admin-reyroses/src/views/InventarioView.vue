<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Inventario</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          Historial de ajustes de stock — {{ inventarioStore.total }} registro{{ inventarioStore.total !== 1 ? 's' : ''
          }}
        </p>
      </div>
      <button @click="showForm = true"
        class="bg-brass-500 hover:bg-brass-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
        <Plus class="w-4 h-4" :stroke-width="2" />
        Nuevo ajuste
      </button>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div v-if="inventarioStore.movimientos.length === 0" class="text-center py-20">
        <div
          :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
          <ClipboardList :class="['w-8 h-8', isDarkTheme ? 'text-ink-600' : 'text-gray-400']" :stroke-width="1.5" />
        </div>
        <p :class="['text-base', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">No hay ajustes registrados</p>
        <p :class="['text-sm mt-1', isDarkTheme ? 'text-ink-700' : 'text-gray-400']">Los ajustes por mermas, daños o
          conteos físicos aparecerán aquí</p>
      </div>

      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
              <tr
                :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                <th class="p-4 font-semibold">Fecha</th>
                <th class="p-4 font-semibold">Producto</th>
                <th class="p-4 font-semibold text-center">Tallo</th>
                <th class="p-4 font-semibold text-center">Tipo</th>
                <th class="p-4 font-semibold text-right">Cantidad</th>
                <th class="p-4 font-semibold text-right">Stock resultante</th>
                <th class="p-4 font-semibold">Motivo</th>
              </tr>
            </thead>
            <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
              <tr v-for="mov in inventarioStore.movimientos" :key="mov.id"
                :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
                <td class="p-4">{{ new Date(mov.fecha).toLocaleDateString() }}</td>
                <td :class="['p-4 font-medium', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ mov.product?.name ||
                  '—' }}</td>
                <td class="p-4 text-center font-mono tabular-nums">{{ mov.tallo }}cm</td>
                <td class="p-4 text-center">
                  <span
                    :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase', isDarkTheme ? 'bg-ink-800 text-ink-400' : 'bg-gray-100 text-gray-600']">
                    {{ mov.tipo }}
                  </span>
                </td>
                <td
                  :class="['p-4 text-right font-bold font-mono tabular-nums', mov.cantidad < 0 ? 'text-brick-500' : 'text-pine-500']">
                  {{ mov.cantidad > 0 ? '+' : '' }}{{ mov.cantidad }}
                </td>
                <td class="p-4 text-right font-mono tabular-nums">{{ mov.stock_resultante }}</td>
                <td :class="['p-4 text-sm max-w-[220px] truncate', isDarkTheme ? 'text-ink-500' : 'text-gray-500']"
                  :title="mov.motivo">
                  {{ mov.motivo || '—' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="px-4">
          <Pagination :current-page="inventarioStore.currentPage" :last-page="inventarioStore.lastPage"
            :from="inventarioStore.from" :to="inventarioStore.to" :total="inventarioStore.total"
            @change="inventarioStore.fetchMovimientos" />
        </div>
      </div>
    </div>

    <MovimientoInventarioForm v-model="showForm" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Plus, ClipboardList } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useInventarioStore } from '@/stores/inventario'
import MovimientoInventarioForm from '@/components/inventario/MovimientoInventarioForm.vue'
import Pagination from '@/components/shared/Pagination.vue'

const { isDarkTheme } = useTheme()
const inventarioStore = useInventarioStore()

const showForm = ref(false)

onMounted(() => {
  inventarioStore.fetchMovimientos()
})
</script>
