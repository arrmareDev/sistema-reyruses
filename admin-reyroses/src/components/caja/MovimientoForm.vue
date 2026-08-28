<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 w-full max-w-sm shadow-2xl', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-5 pb-4 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nuevo Movimiento</h2>
          <button @click="close"
            :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-2 gap-2">
            <button type="button" @click="form.tipo = 'ingreso'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2', form.tipo === 'ingreso' ? 'bg-emerald-500 text-white' : isDarkTheme ? 'bg-[#1e1e24] text-zinc-400' : 'bg-gray-100 text-gray-500']">
              <ArrowDownCircle class="w-4 h-4" :stroke-width="2" />
              Ingreso
            </button>
            <button type="button" @click="form.tipo = 'egreso'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2', form.tipo === 'egreso' ? 'bg-red-500 text-white' : isDarkTheme ? 'bg-[#1e1e24] text-zinc-400' : 'bg-gray-100 text-gray-500']">
              <ArrowUpCircle class="w-4 h-4" :stroke-width="2" />
              Egreso
            </button>
          </div>

          <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500">Fecha</label>
            <input v-model="form.fecha" type="date" required :class="inputClass" />
          </div>
          <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500">Monto
              (S/)</label>
            <input v-model="form.monto" type="number" step="0.01" min="0.01" required :class="inputClass" />
          </div>
          <div>
            <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500">Concepto</label>
            <input v-model="form.concepto" type="text" placeholder="Ej: Venta al contado, alquiler local" required
              :class="inputClass" />
          </div>

          <button type="submit"
            class="w-full py-3 mt-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
            Registrar
          </button>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { X, ArrowDownCircle, ArrowUpCircle } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCajaStore } from '@/stores/caja'

const { isDarkTheme } = useTheme()
const cajaStore = useCajaStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const emptyForm = () => ({
  tipo: 'ingreso',
  fecha: new Date().toISOString().slice(0, 10),
  monto: '',
  concepto: '',
})

const form = ref(emptyForm())

const inputClass = computed(() => [
  'w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 outline-none',
  isDarkTheme.value ? 'bg-[#1e1e24] border border-[#2a2a32] text-white' : 'bg-gray-100 border border-gray-200 text-gray-900',
])

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const saved = await cajaStore.crearMovimiento(form.value)
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
