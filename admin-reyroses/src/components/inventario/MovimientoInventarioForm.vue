<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 w-full max-w-md shadow-2xl max-h-[90vh] overflow-y-auto', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-5 pb-4 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <h2 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nuevo Ajuste</h2>
          <button @click="close"
            :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white hover:bg-ink-800' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label :class="labelClass">Producto</label>
            <select v-model="form.product_id" required :class="inputClass" @change="form.tallo = ''">
              <option value="" disabled>Selecciona...</option>
              <option v-for="product in catalogoStore.products" :key="product.id" :value="product.id">{{ product.name }}
              </option>
            </select>
          </div>

          <div>
            <label :class="labelClass">Tallo</label>
            <select v-model="form.tallo" required :class="inputClass">
              <option value="" disabled>Selecciona...</option>
              <option v-for="tallo in TALLOS" :key="tallo" :value="tallo">{{ tallo }}cm</option>
            </select>
            <p v-if="stockActual !== null"
              :class="['text-[11px] mt-1 font-mono tabular-nums', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
              Stock actual: {{ stockActual }} paquetes
            </p>
          </div>

          <div>
            <label :class="labelClass">Tipo de ajuste</label>
            <select v-model="form.tipo" required :class="inputClass">
              <option v-for="tipo in TIPOS_AJUSTE" :key="tipo" :value="tipo">{{ tipo }}</option>
            </select>
          </div>

          <div class="grid grid-cols-2 gap-2">
            <button type="button" @click="direccion = 'restar'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2', direccion === 'restar' ? 'bg-brick-500 text-white' : isDarkTheme ? 'bg-ink-800 text-ink-500' : 'bg-gray-100 text-gray-500']">
              <Minus class="w-4 h-4" :stroke-width="2.5" />
              Restar stock
            </button>
            <button type="button" @click="direccion = 'sumar'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center justify-center gap-2', direccion === 'sumar' ? 'bg-pine-500 text-white' : isDarkTheme ? 'bg-ink-800 text-ink-500' : 'bg-gray-100 text-gray-500']">
              <PlusIcon class="w-4 h-4" :stroke-width="2.5" />
              Sumar stock
            </button>
          </div>

          <div>
            <label :class="labelClass">Cantidad (paquetes)</label>
            <input v-model="form.cantidad" type="number" min="1" required
              :class="[...inputClassArr, 'font-mono tabular-nums']" />
            <p v-if="resultadoPreview !== null"
              :class="['text-[11px] mt-1 font-mono tabular-nums font-semibold', resultadoPreview < 0 ? 'text-brick-500' : (isDarkTheme ? 'text-ink-500' : 'text-gray-500')]">
              {{ resultadoPreview < 0 ? 'Esto dejaría el stock en negativo — no se puede guardar' : `Quedaría en:
                ${resultadoPreview} paquetes` }} </p>
          </div>

          <div>
            <label :class="labelClass">Fecha</label>
            <input v-model="form.fecha" type="date" required :class="inputClass" />
          </div>

          <div>
            <label :class="labelClass">Motivo (opcional)</label>
            <textarea v-model="form.motivo" rows="2" placeholder="Ej: 5 tallos dañados en transporte"
              :class="inputClass"></textarea>
          </div>

          <button type="submit" :disabled="resultadoPreview !== null && resultadoPreview < 0"
            class="w-full py-3 mt-2 bg-brass-500 hover:bg-brass-600 disabled:opacity-40 disabled:pointer-events-none text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all">
            Registrar ajuste
          </button>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { X, Minus, Plus as PlusIcon } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useInventarioStore, TIPOS_AJUSTE } from '@/stores/inventario'
import { useCatalogoStore } from '@/stores/catalogo'

const TALLOS = [50, 60, 70, 80, 90]

const { isDarkTheme } = useTheme()
const inventarioStore = useInventarioStore()
const catalogoStore = useCatalogoStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const emptyForm = () => ({
  product_id: '',
  tallo: '',
  tipo: 'Merma',
  cantidad: '',
  fecha: new Date().toISOString().slice(0, 10),
  motivo: '',
})

const form = ref(emptyForm())
const direccion = ref('restar')

const labelClass = 'block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600'
const inputClassArr = [
  'w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none',
]
const inputClass = computed(() => [
  ...inputClassArr,
  isDarkTheme.value ? 'bg-ink-800 border border-ink-700 text-white' : 'bg-gray-100 border border-gray-200 text-gray-900',
])

const stockActual = computed(() => {
  if (!form.value.product_id || !form.value.tallo) return null
  const product = catalogoStore.products.find((p) => p.id === Number(form.value.product_id))
  return product ? Number(product['stock_' + form.value.tallo]) || 0 : null
})

const resultadoPreview = computed(() => {
  if (stockActual.value === null || !form.value.cantidad) return null
  const delta = direccion.value === 'restar' ? -Math.abs(Number(form.value.cantidad)) : Math.abs(Number(form.value.cantidad))
  return stockActual.value + delta
})

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const cantidadConSigno = direccion.value === 'restar' ? -Math.abs(Number(form.value.cantidad)) : Math.abs(Number(form.value.cantidad))

  const saved = await inventarioStore.crearMovimiento({
    ...form.value,
    cantidad: cantidadConSigno,
  })

  if (saved) {
    form.value = emptyForm()
    direccion.value = 'restar'
    close()
  }
}

watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) {
      form.value = emptyForm()
      direccion.value = 'restar'
    }
  },
)

onMounted(() => {
  if (catalogoStore.products.length === 0) catalogoStore.fetchProducts()
})
</script>
