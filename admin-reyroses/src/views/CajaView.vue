<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 z-10', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Caja</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Control de ingresos y egresos</p>
      </div>
      <div class="flex gap-2">
        <button @click="showArqueoForm = true"
          :class="['px-4 py-2.5 rounded-xl font-semibold text-sm transition-all flex items-center gap-2', isDarkTheme ? 'bg-ink-800 text-ink-300 hover:bg-ink-700 border border-ink-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200']">
          <Scale class="w-4 h-4" :stroke-width="2" />
          Arqueo
        </button>
        <button @click="showMovimientoForm = true"
          class="bg-brass-500 hover:bg-brass-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
          <Plus class="w-4 h-4" :stroke-width="2" />
          Movimiento
        </button>
      </div>
    </header>

    <!-- Barra de estadísticas -->
    <div
      :class="['px-4 md:px-8 py-4 grid grid-cols-3 gap-3', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
        <div class="flex items-center gap-2 mb-1">
          <Wallet class="w-3.5 h-3.5 text-brass-500" :stroke-width="2" />
          <span
            :class="['text-[10px] font-semibold uppercase tracking-wide', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Saldo
            actual</span>
        </div>
        <p class="text-lg font-bold font-mono tabular-nums text-brass-500">S/ {{ cajaStore.saldoActual.toFixed(2) }}</p>
      </div>
      <div :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
        <div class="flex items-center gap-2 mb-1">
          <ArrowDownCircle class="w-3.5 h-3.5 text-pine-500" :stroke-width="2" />
          <span
            :class="['text-[10px] font-semibold uppercase tracking-wide', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Total
            ingresos</span>
        </div>
        <p class="text-lg font-bold font-mono tabular-nums text-pine-500">S/ {{ cajaStore.totalIngresos.toFixed(2) }}
        </p>
      </div>
      <div :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
        <div class="flex items-center gap-2 mb-1">
          <ArrowUpCircle class="w-3.5 h-3.5 text-brick-500" :stroke-width="2" />
          <span
            :class="['text-[10px] font-semibold uppercase tracking-wide', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">Total
            egresos</span>
        </div>
        <p class="text-lg font-bold font-mono tabular-nums text-brick-500">S/ {{ cajaStore.totalEgresos.toFixed(2) }}
        </p>
      </div>
    </div>

    <div
      :class="['px-4 md:px-8 pt-4 flex gap-1', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <button v-for="tab in ['Movimientos', 'Arqueos']" :key="tab" @click="activeTab = tab" :class="[
        'px-5 py-3 text-sm font-medium tracking-wide transition-all rounded-t-lg',
        activeTab === tab
          ? isDarkTheme ? 'text-brass-400 bg-ink-950' : 'text-brass-600 bg-gray-100'
          : isDarkTheme ? 'text-ink-600 hover:text-ink-400' : 'text-gray-500 hover:text-gray-700',
      ]">
        {{ tab }}
      </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <!-- Movimientos -->
      <div v-if="activeTab === 'Movimientos'"
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <table class="w-full text-left">
          <thead>
            <tr
              :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
              <th class="p-4 font-semibold">Fecha</th>
              <th class="p-4 font-semibold">Concepto</th>
              <th class="p-4 font-semibold text-center">Tipo</th>
              <th class="p-4 font-semibold text-right">Monto</th>
            </tr>
          </thead>
          <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
            <tr v-for="mov in cajaStore.movimientos" :key="mov.id"
              :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
              <td class="p-4">{{ new Date(mov.fecha).toLocaleDateString() }}</td>
              <td class="p-4 font-medium">{{ mov.concepto }}</td>
              <td class="p-4 text-center">
                <span
                  :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase', mov.tipo === 'ingreso' ? 'bg-pine-500/20 text-pine-400' : 'bg-brick-500/20 text-brick-400']">
                  {{ mov.tipo }}
                </span>
              </td>
              <td
                :class="['p-4 text-right font-bold font-mono tabular-nums', mov.tipo === 'ingreso' ? 'text-pine-500' : 'text-brick-500']">
                {{ mov.tipo === 'ingreso' ? '+' : '-' }} S/ {{ Number(mov.monto).toFixed(2) }}
              </td>
            </tr>
            <tr v-if="cajaStore.movimientos.length === 0">
              <td colspan="4" :class="['p-10 text-center', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">No hay
                movimientos registrados.</td>
            </tr>
          </tbody>
        </table>

        <div class="px-4">
          <Pagination :current-page="cajaStore.movCurrentPage" :last-page="cajaStore.movLastPage"
            :from="cajaStore.movFrom" :to="cajaStore.movTo" :total="cajaStore.movTotal"
            @change="cajaStore.fetchMovimientos" />
        </div>
      </div>

      <!-- Arqueos -->
      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <table class="w-full text-left">
          <thead>
            <tr
              :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
              <th class="p-4 font-semibold">Fecha</th>
              <th class="p-4 font-semibold text-right">Saldo sistema</th>
              <th class="p-4 font-semibold text-right">Saldo contado</th>
              <th class="p-4 font-semibold text-right">Diferencia</th>
            </tr>
          </thead>
          <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
            <tr v-for="arqueo in cajaStore.arqueos" :key="arqueo.id"
              :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
              <td class="p-4">{{ new Date(arqueo.fecha).toLocaleDateString() }}</td>
              <td class="p-4 text-right font-mono tabular-nums">S/ {{ Number(arqueo.saldo_sistema).toFixed(2) }}</td>
              <td class="p-4 text-right font-mono tabular-nums">S/ {{ Number(arqueo.saldo_contado).toFixed(2) }}</td>
              <td
                :class="['p-4 text-right font-bold font-mono tabular-nums', Number(arqueo.diferencia) === 0 ? 'text-pine-500' : 'text-amber-500']">
                S/ {{ Number(arqueo.diferencia).toFixed(2) }}
              </td>
            </tr>
            <tr v-if="cajaStore.arqueos.length === 0">
              <td colspan="4" :class="['p-10 text-center', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">No hay
                arqueos registrados.</td>
            </tr>
          </tbody>
        </table>

        <div class="px-4">
          <Pagination :current-page="cajaStore.arqCurrentPage" :last-page="cajaStore.arqLastPage"
            :from="cajaStore.arqFrom" :to="cajaStore.arqTo" :total="cajaStore.arqTotal"
            @change="cajaStore.fetchArqueos" />
        </div>
      </div>
    </div>

    <MovimientoForm v-model="showMovimientoForm" />
    <ArqueoForm v-model="showArqueoForm" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Plus, Scale, Wallet, ArrowDownCircle, ArrowUpCircle } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCajaStore } from '@/stores/caja'
import MovimientoForm from '@/components/caja/MovimientoForm.vue'
import ArqueoForm from '@/components/caja/ArqueoForm.vue'
import Pagination from '@/components/shared/Pagination.vue'

const { isDarkTheme } = useTheme()
const cajaStore = useCajaStore()

const activeTab = ref('Movimientos')
const showMovimientoForm = ref(false)
const showArqueoForm = ref(false)

onMounted(() => {
  cajaStore.fetchMovimientos()
  cajaStore.fetchArqueos()
})
</script>
