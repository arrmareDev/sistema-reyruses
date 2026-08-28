<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-2xl shadow-2xl max-h-[92vh] overflow-y-auto', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nuevo Pedido</h2>
          <button @click="close"
            :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
              <label :class="labelClass">Nombre del cliente</label>
              <input v-model="form.customer.name" type="text" required :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Celular</label>
              <input v-model="form.customer.phone" type="text" required :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Dirección</label>
              <input v-model="form.customer.address" type="text" required :class="inputClass" />
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2">
            <button type="button" @click="form.tipo_pago = 'Contado'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all', form.tipo_pago === 'Contado' ? 'bg-emerald-500 text-white' : isDarkTheme ? 'bg-[#1e1e24] text-zinc-400' : 'bg-gray-100 text-gray-500']">
              Contado
            </button>
            <button type="button" @click="form.tipo_pago = 'Credito'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all', form.tipo_pago === 'Credito' ? 'bg-amber-500 text-white' : isDarkTheme ? 'bg-[#1e1e24] text-zinc-400' : 'bg-gray-100 text-gray-500']">
              Crédito
            </button>
          </div>

          <div v-if="form.tipo_pago === 'Credito'">
            <label :class="labelClass">Fecha límite de pago</label>
            <input v-model="form.fecha_limite_pago" type="date" required :class="inputClass" />
          </div>

          <!-- Items -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3
                :class="['text-xs font-semibold uppercase tracking-widest', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                Productos
              </h3>
              <button type="button" @click="addItem"
                class="text-xs font-semibold text-emerald-500 hover:text-emerald-400 flex items-center gap-1">
                <Plus class="w-3.5 h-3.5" :stroke-width="2.5" />
                Agregar producto
              </button>
            </div>

            <div class="space-y-3">
              <div v-for="(item, index) in form.items" :key="index"
                :class="['p-3 rounded-xl border grid grid-cols-12 gap-2 items-end', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
                <div class="col-span-5">
                  <label :class="labelClass">Variedad</label>
                  <select v-model="item.product_id" required :class="inputClass" @change="onProductChange(item)">
                    <option value="" disabled>Selecciona...</option>
                    <option v-for="product in catalogoStore.products" :key="product.id" :value="product.id">{{
                      product.name }}</option>
                  </select>
                </div>
                <div class="col-span-3">
                  <label :class="labelClass">Tallo</label>
                  <select v-model="item.tallo" required :class="inputClass" @change="onTalloChange(item)">
                    <option v-for="tallo in TALLOS" :key="tallo" :value="tallo">{{ tallo }}cm</option>
                  </select>
                </div>
                <div class="col-span-2">
                  <label :class="labelClass">Cant.</label>
                  <input v-model="item.quantity" type="number" min="1" required :class="inputClass" />
                </div>
                <div class="col-span-1">
                  <label :class="labelClass">P. Unit.</label>
                  <input v-model="item.price" type="number" step="0.01" min="0" required :class="inputClass" />
                </div>
                <div class="col-span-1 flex justify-center">
                  <button v-if="form.items.length > 1" type="button" @click="removeItem(index)"
                    class="text-red-400 hover:text-red-500 pb-2.5">
                    <Trash2 class="w-4 h-4" :stroke-width="2" />
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div
            :class="['flex justify-between items-center pt-4 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
            <span :class="['font-semibold', isDarkTheme ? 'text-zinc-400' : 'text-gray-500']">Total</span>
            <span class="text-xl font-bold text-emerald-500">S/ {{ total.toFixed(2) }}</span>
          </div>

          <div class="flex justify-end gap-3">
            <button type="button" @click="close"
              :class="['px-5 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'text-zinc-400 hover:bg-[#1e1e24]' : 'text-gray-500 hover:bg-gray-100']">
              Cancelar
            </button>
            <button type="submit"
              class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
              Registrar pedido
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { X, Plus, Trash2 } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { usePedidosStore } from '@/stores/pedidos'
import { useCatalogoStore } from '@/stores/catalogo'

const TALLOS = [50, 60, 70, 80, 90]

const { isDarkTheme } = useTheme()
const pedidosStore = usePedidosStore()
const catalogoStore = useCatalogoStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const emptyItem = () => ({ product_id: '', tallo: 50, quantity: 1, price: '' })

const emptyForm = () => ({
  customer: { name: '', phone: '', address: '' },
  tipo_pago: 'Contado',
  fecha_limite_pago: '',
  items: [emptyItem()],
})

const form = ref(emptyForm())

const labelClass = 'block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-zinc-500'
const inputClass = computed(() => [
  'w-full px-3 py-2 rounded-lg text-sm font-medium focus:ring-2 focus:ring-emerald-500/50 outline-none',
  isDarkTheme.value ? 'bg-[#16161a] border border-[#2a2a32] text-white' : 'bg-white border border-gray-200 text-gray-900',
])

// Cuando cambia el producto o el tallo, autocompleta el precio con el del catálogo
const autofillPrice = (item) => {
  const product = catalogoStore.products.find((p) => p.id === Number(item.product_id))
  if (product) item.price = product['price_' + item.tallo] || ''
}
const onProductChange = (item) => autofillPrice(item)
const onTalloChange = (item) => autofillPrice(item)

const addItem = () => form.value.items.push(emptyItem())
const removeItem = (index) => form.value.items.splice(index, 1)

const total = computed(() =>
  form.value.items.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity) || 0), 0),
)

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const payload = {
    customer: form.value.customer,
    total: total.value,
    tipo_pago: form.value.tipo_pago,
    fecha_limite_pago: form.value.tipo_pago === 'Credito' ? form.value.fecha_limite_pago : null,
    items: form.value.items.map((item) => {
      const product = catalogoStore.products.find((p) => p.id === Number(item.product_id))
      return {
        id: `${item.product_id}-${item.tallo}`,
        name: `${product ? product.name : ''} (${item.tallo}cm)`,
        price: item.price,
        quantity: item.quantity,
      }
    }),
  }

  const saved = await pedidosStore.crearPedido(payload)
  if (saved) {
    form.value = emptyForm()
    close()
  }
}

watch(
  () => props.modelValue,
  (isOpen) => { if (isOpen) form.value = emptyForm() },
)

onMounted(() => {
  if (catalogoStore.products.length === 0) catalogoStore.fetchProducts()
})
</script>
