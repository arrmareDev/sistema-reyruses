<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 w-full max-w-sm shadow-2xl', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-5 pb-4 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nuevo Arqueo</h2>
          <button @click="close"
            :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <div
          :class="['p-3 rounded-xl mb-4 flex justify-between items-center text-sm', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
          <span :class="isDarkTheme ? 'text-zinc-400' : 'text-gray-600'">Saldo según el sistema</span>
          <span :class="['font-bold font-mono', isDarkTheme ? 'text-white' : 'text-gray-900']">S/ {{
            cajaStore.saldoActual.toFixed(2) }}</span>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500">Saldo contado
              físicamente (S/)</label>
            <input v-model="form.saldo_contado" type="number" step="0.01" min="0" required :class="inputClass" />
          </div>

          <p v-if="form.saldo_contado !== ''"
            :class="['text-xs font-semibold', diferencia === 0 ? 'text-emerald-500' : 'text-amber-500']">
            Diferencia: S/ {{ diferencia.toFixed(2) }}
          </p>

          <div>
            <label
              class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500">Observaciones</label>
            <textarea v-model="form.observaciones" rows="2" placeholder="Opcional" :class="inputClass"></textarea>
          </div>

          <button type="submit"
            class="w-full py-3 mt-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
            Registrar arqueo
          </button>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { X } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCajaStore } from '@/stores/caja'

const { isDarkTheme } = useTheme()
const cajaStore = useCajaStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const emptyForm = () => ({ saldo_contado: '', observaciones: '' })
const form = ref(emptyForm())

const diferencia = computed(() => (Number(form.value.saldo_contado) || 0) - cajaStore.saldoActual)

const inputClass = computed(() => [
  'w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 outline-none',
  isDarkTheme.value ? 'bg-[#1e1e24] border border-[#2a2a32] text-white' : 'bg-gray-100 border border-gray-200 text-gray-900',
])

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const saved = await cajaStore.crearArqueo(form.value)
  if (saved) {
    form.value = emptyForm()
    close()
  }
}

watch(
  () => props.modelValue,
  (isOpen) => { if (isOpen) form.value = emptyForm() },
)
</script>
