<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 z-10', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Caja</h1>
        <p class="text-sm mt-0.5 text-emerald-500 font-bold">
          Saldo actual: S/ {{ cajaStore.saldoActual.toFixed(2) }}
        </p>
      </div>
      <div class="flex gap-2">
        <button @click="showArqueoForm = true"
          :class="['px-4 py-2.5 rounded-xl font-semibold text-sm transition-all flex items-center gap-2', isDarkTheme ? 'bg-[#1e1e24] text-zinc-300 hover:bg-[#2a2a32] border border-[#2a2a32]' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200']">
          <Scale class="w-4 h-4" :stroke-width="2" />
          Arqueo
        </button>
        <button @click="showMovimientoForm = true"
          class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
          <Plus class="w-4 h-4" :stroke-width="2" />
          Movimiento
        </button>
      </div>
    </header>

    <div
      :class="['px-4 md:px-8 pt-4 flex gap-1', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
      <button v-for="tab in ['Movimientos', 'Arqueos']" :key="tab" @click="activeTab = tab" :class="[
        'px-5 py-3 text-sm font-medium tracking-wide transition-all rounded-t-lg',
        activeTab === tab
          ? isDarkTheme ? 'text-emerald-400 bg-[#0f0f12]' : 'text-emerald-600 bg-gray-100'
          : isDarkTheme ? 'text-zinc-500 hover:text-zinc-300' : 'text-gray-500 hover:text-gray-700',
      ]">
        {{ tab }}
      </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <!-- Movimientos -->
      <div v-if="activeTab === 'Movimientos'"
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32]' : 'bg-white border-gray-200']">
        <table class="w-full text-left">
          <thead>
            <tr
              :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-[#1e1e24] text-zinc-500 border-b border-[#2a2a32]' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
              <th class="p-4 font-semibold">Fecha</th>
              <th class="p-4 font-semibold">Concepto</th>
              <th class="p-4 font-semibold text-center">Tipo</th>
              <th class="p-4 font-semibold text-right">Monto</th>
            </tr>
          </thead>
          <tbody :class="['text-sm', isDarkTheme ? 'text-zinc-300' : 'text-gray-700']">
            <tr v-for="mov in cajaStore.movimientos" :key="mov.id"
              :class="['transition-colors', isDarkTheme ? 'border-b border-[#2a2a32] hover:bg-[#1e1e24]' : 'border-b border-gray-100 hover:bg-gray-50']">
              <td class="p-4">{{ new Date(mov.fecha).toLocaleDateString() }}</td>
              <td class="p-4 font-medium">{{ mov.concepto }}</td>
              <td class="p-4 text-center">
                <span
                  :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase', mov.tipo === 'ingreso' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400']">
                  {{ mov.tipo }}
                </span>
              </td>
              <td
                :class="['p-4 text-right font-bold font-mono', mov.tipo === 'ingreso' ? 'text-emerald-500' : 'text-red-500']">
                {{ mov.tipo === 'ingreso' ? '+' : '-' }} S/ {{ Number(mov.monto).toFixed(2) }}
              </td>
            </tr>
            <tr v-if="cajaStore.movimientos.length === 0">
              <td colspan="4" :class="['p-10 text-center', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">No hay
                movimientos registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Arqueos -->
      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32]' : 'bg-white border-gray-200']">
        <table class="w-full text-left">
          <thead>
            <tr
              :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-[#1e1e24] text-zinc-500 border-b border-[#2a2a32]' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
              <th class="p-4 font-semibold">Fecha</th>
              <th class="p-4 font-semibold text-right">Saldo sistema</th>
              <th class="p-4 font-semibold text-right">Saldo contado</th>
              <th class="p-4 font-semibold text-right">Diferencia</th>
            </tr>
          </thead>
          <tbody :class="['text-sm', isDarkTheme ? 'text-zinc-300' : 'text-gray-700']">
            <tr v-for="arqueo in cajaStore.arqueos" :key="arqueo.id"
              :class="['transition-colors', isDarkTheme ? 'border-b border-[#2a2a32] hover:bg-[#1e1e24]' : 'border-b border-gray-100 hover:bg-gray-50']">
              <td class="p-4">{{ new Date(arqueo.fecha).toLocaleDateString() }}</td>
              <td class="p-4 text-right font-mono">S/ {{ Number(arqueo.saldo_sistema).toFixed(2) }}</td>
              <td class="p-4 text-right font-mono">S/ {{ Number(arqueo.saldo_contado).toFixed(2) }}</td>
              <td
                :class="['p-4 text-right font-bold font-mono', Number(arqueo.diferencia) === 0 ? 'text-emerald-500' : 'text-amber-500']">
                S/ {{ Number(arqueo.diferencia).toFixed(2) }}
              </td>
            </tr>
            <tr v-if="cajaStore.arqueos.length === 0">
              <td colspan="4" :class="['p-10 text-center', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">No hay
                arqueos registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <MovimientoForm v-model="showMovimientoForm" />
    <ArqueoForm v-model="showArqueoForm" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Plus, Scale } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCajaStore } from '@/stores/caja'
import MovimientoForm from '@/components/caja/MovimientoForm.vue'
import ArqueoForm from '@/components/caja/ArqueoForm.vue'

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
