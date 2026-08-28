<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Fondo de Inversión
        </h1>
        <p class="text-sm mt-0.5 text-brass-500 font-bold font-mono tabular-nums">
          Saldo disponible: S/ {{ inversionStore.saldoActual.toFixed(2) }}
        </p>
      </div>
      <button @click="showForm = true"
        class="bg-brass-500 hover:bg-brass-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
        <Plus class="w-4 h-4" :stroke-width="2" />
        Depositar
      </button>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <table class="w-full text-left">
          <thead>
            <tr
              :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
              <th class="p-4 font-semibold">Fecha</th>
              <th class="p-4 font-semibold">Descripción</th>
              <th class="p-4 font-semibold text-center">Tipo</th>
              <th class="p-4 font-semibold text-right">Monto</th>
            </tr>
          </thead>
          <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
            <tr v-for="mov in inversionStore.movimientos" :key="mov.id"
              :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
              <td class="p-4">{{ new Date(mov.fecha).toLocaleDateString() }}</td>
              <td class="p-4 font-medium">
                {{ mov.descripcion || (mov.compra_id ? `Retiro para compra #${mov.compra_id.toString().padStart(4,
                  '0')}` : '—') }}
              </td>
              <td class="p-4 text-center">
                <span
                  :class="['px-2.5 py-1 rounded-full text-[10px] font-bold uppercase', mov.tipo === 'deposito' ? 'bg-pine-500/20 text-pine-400' : 'bg-amber-500/20 text-amber-400']">
                  {{ mov.tipo }}
                </span>
              </td>
              <td
                :class="['p-4 text-right font-bold font-mono tabular-nums', mov.tipo === 'deposito' ? 'text-pine-500' : 'text-amber-500']">
                {{ mov.tipo === 'deposito' ? '+' : '-' }} S/ {{ Number(mov.monto).toFixed(2) }}
              </td>
            </tr>
            <tr v-if="inversionStore.movimientos.length === 0">
              <td colspan="4" :class="['p-10 text-center', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
                No hay movimientos en el fondo todavía.
              </td>
            </tr>
          </tbody>
        </table>

        <div class="px-4">
          <Pagination :current-page="inversionStore.currentPage" :last-page="inversionStore.lastPage"
            :from="inversionStore.from" :to="inversionStore.to" :total="inversionStore.total"
            @change="inversionStore.fetchMovimientos" />
        </div>
      </div>
    </div>

    <Teleport to="body">
      <div v-if="showForm"
        :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
        <div
          :class="['rounded-2xl p-6 w-full max-w-sm shadow-2xl', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
          <div
            :class="['flex items-center justify-between mb-5 pb-4 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
            <h2 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Depositar capital</h2>
            <button @click="showForm = false"
              :class="['p-1', isDarkTheme ? 'text-ink-600 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
              <X class="w-5 h-5" :stroke-width="2" />
            </button>
          </div>

          <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
              <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600">Fecha</label>
              <input v-model="form.fecha" type="date" required :class="inputClass" />
            </div>
            <div>
              <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600">Monto
                (S/)</label>
              <input v-model="form.monto" type="number" step="0.01" min="0.01" required :class="inputClass" />
            </div>
            <div>
              <label
                class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600">Descripción</label>
              <input v-model="form.descripcion" type="text" placeholder="Opcional" :class="inputClass" />
            </div>

            <button type="submit"
              class="w-full py-3 mt-2 bg-brass-500 hover:bg-brass-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all">
              Registrar depósito
            </button>
          </form>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Plus, X } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useInversionStore } from '@/stores/inversion'
import Pagination from '@/components/shared/Pagination.vue'

const { isDarkTheme } = useTheme()
const inversionStore = useInversionStore()

const showForm = ref(false)

const emptyForm = () => ({
  fecha: new Date().toISOString().slice(0, 10),
  monto: '',
  descripcion: '',
})
const form = ref(emptyForm())

const inputClass = computed(() => [
  'w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none',
  isDarkTheme.value ? 'bg-ink-800 border border-ink-700 text-white' : 'bg-gray-100 border border-gray-200 text-gray-900',
])

const handleSubmit = async () => {
  const saved = await inversionStore.depositar(form.value)
  if (saved) {
    form.value = emptyForm()
    showForm.value = false
  }
}

onMounted(() => {
  inversionStore.fetchMovimientos()
})
</script>
