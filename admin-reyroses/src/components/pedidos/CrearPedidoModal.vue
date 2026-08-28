<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-2xl shadow-2xl max-h-[92vh] overflow-y-auto', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nuevo Pedido</h2>
          <button @click="close"
            :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white hover:bg-ink-800' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100']">
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
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all', form.tipo_pago === 'Contado' ? 'bg-pine-500 text-white' : isDarkTheme ? 'bg-ink-800 text-ink-500' : 'bg-gray-100 text-gray-500']">
              Contado
            </button>
            <button type="button" @click="form.tipo_pago = 'Credito'"
              :class="['py-2.5 rounded-xl text-sm font-semibold transition-all', form.tipo_pago === 'Credito' ? 'bg-amber-500 text-white' : isDarkTheme ? 'bg-ink-800 text-ink-500' : 'bg-gray-100 text-gray-500']">
              Crédito
            </button>
          </div>

          <div v-if="form.tipo_pago === 'Credito'">
            <label :class="labelClass">Fecha límite de pago</label>
            <input v-model="form.fecha_limite_pago" type="date" required :class="inputClass" />
          </div>

          <!-- Voucher de pago -->
          <div>
            <label :class="labelClass">Voucher de pago (opcional)</label>
            <label
              :class="['flex items-center gap-3 px-4 py-3 rounded-xl cursor-pointer transition-colors', isDarkTheme ? 'bg-ink-800 border border-ink-700 hover:border-ink-600' : 'bg-gray-100 border border-gray-200 hover:border-gray-300']">
              <Paperclip :class="['w-4 h-4 shrink-0', isDarkTheme ? 'text-ink-500' : 'text-gray-400']"
                :stroke-width="2" />
              <span
                :class="['text-sm truncate', voucherFile ? (isDarkTheme ? 'text-white font-medium' : 'text-gray-900 font-medium') : (isDarkTheme ? 'text-ink-600' : 'text-gray-400')]">
                {{ voucherFile ? voucherFile.name : 'Adjuntar captura o foto del comprobante...' }}
              </span>
              <input type="file" accept="image/*,application/pdf" @change="onVoucherChange" class="hidden" />
            </label>
          </div>

          <!-- Items -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3
                :class="['text-xs font-semibold uppercase tracking-widest', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
                Productos
              </h3>
              <button type="button" @click="addItem"
                class="text-xs font-semibold text-brass-500 hover:text-brass-400 flex items-center gap-1">
                <Plus class="w-3.5 h-3.5" :stroke-width="2.5" />
                Agregar producto
              </button>
            </div>

            <div class="space-y-3">
              <div v-for="(item, index) in form.items" :key="index"
                :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
                <div class="flex items-start gap-3 mb-3">
                  <div class="flex-1">
                    <label :class="labelClass">Variedad</label>
                    <select v-model="item.product_id" required :class="inputClass" @change="onProductChange(item)">
                      <option value="" disabled>Selecciona...</option>
                      <option v-for="product in catalogoStore.products" :key="product.id" :value="product.id">{{
                        product.name }}</option>
                    </select>
                  </div>
                  <button v-if="form.items.length > 1" type="button" @click="removeItem(index)"
                    :class="['p-2 mt-5 rounded-lg text-brick-400 hover:bg-brick-500/10 hover:text-brick-500 transition-colors shrink-0']"
                    title="Quitar producto">
                    <Trash2 class="w-4 h-4" :stroke-width="2" />
                  </button>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                  <div>
                    <label :class="labelClass">Tallo</label>
                    <select v-model="item.tallo" required :class="inputClass" @change="onTalloChange(item)">
                      <option v-for="tallo in TALLOS" :key="tallo" :value="tallo">{{ tallo }}cm</option>
                    </select>
                  </div>
                  <div>
                    <label :class="labelClass">Cantidad (paquetes)</label>
                    <input v-model="item.quantity" type="number" min="1" required
                      :class="[...inputClass, 'font-mono tabular-nums']" />
                    <p v-if="item.product_id"
                      :class="['text-[10px] mt-1 font-mono tabular-nums', excedeStock(item) ? 'text-brick-500 font-semibold' : (isDarkTheme ? 'text-ink-600' : 'text-gray-400')]">
                      {{ excedeStock(item) ? '¡Supera el stock! ' : 'Disponible: ' }}{{ stockDisponible(item) }}
                      paquetes
                    </p>
                  </div>
                  <div>
                    <label :class="labelClass">Precio / paquete (S/)</label>
                    <input v-model="item.price" type="number" step="0.01" min="0" required
                      :class="[...inputClass, 'font-mono tabular-nums']" />
                  </div>
                  <div>
                    <label :class="labelClass">Subtotal</label>
                    <p
                      :class="['px-3 py-2 rounded-lg text-sm font-bold font-mono tabular-nums', isDarkTheme ? 'bg-ink-900 text-pine-400 border border-ink-700' : 'bg-white text-pine-600 border border-gray-200']">
                      S/ {{ ((Number(item.price) || 0) * (Number(item.quantity) || 0)).toFixed(2) }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            :class="['flex justify-between items-center pt-4 border-t', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
            <span :class="['font-semibold', isDarkTheme ? 'text-ink-400' : 'text-gray-500']">Total</span>
            <span class="text-xl font-bold font-mono tabular-nums text-pine-500">S/ {{ total.toFixed(2) }}</span>
          </div>

          <div class="flex justify-end gap-3">
            <button type="button" @click="close"
              :class="['px-5 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'text-ink-600 hover:bg-ink-800 hover:text-white' : 'text-gray-500 hover:bg-gray-100']">
              Cancelar
            </button>
            <button type="submit" :disabled="submitting"
              class="px-5 py-3 bg-brass-500 hover:bg-brass-600 disabled:opacity-60 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all">
              {{ submitting ? 'Guardando...' : 'Registrar pedido' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { X, Plus, Trash2, Paperclip } from 'lucide-vue-next'
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
const voucherFile = ref(null)
const submitting = ref(false)

const labelClass = 'block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600'
const inputClass = computed(() => [
  'w-full px-3 py-2 rounded-lg text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none',
  isDarkTheme.value ? 'bg-ink-900 border border-ink-700 text-white' : 'bg-white border border-gray-200 text-gray-900',
])

const onVoucherChange = (event) => {
  voucherFile.value = event.target.files[0] || null
}

// Cuando cambia el producto o el tallo, autocompleta el precio con el del catálogo
const autofillPrice = (item) => {
  const product = catalogoStore.products.find((p) => p.id === Number(item.product_id))
  if (product) item.price = product['price_' + item.tallo] || ''
}
const onProductChange = (item) => autofillPrice(item)
const onTalloChange = (item) => autofillPrice(item)

// El stock del catálogo está en paquetes — esto le muestra al vendedor
// cuántos paquetes quedan de esa variedad/tallo mientras arma el pedido.
const stockDisponible = (item) => {
  const product = catalogoStore.products.find((p) => p.id === Number(item.product_id))
  return product ? Number(product['stock_' + item.tallo]) || 0 : 0
}
const excedeStock = (item) => Number(item.quantity) > stockDisponible(item)

const addItem = () => form.value.items.push(emptyItem())
const removeItem = (index) => form.value.items.splice(index, 1)

const total = computed(() =>
  form.value.items.reduce((sum, item) => sum + (Number(item.price) || 0) * (Number(item.quantity) || 0), 0),
)

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  submitting.value = true

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

  const order = await pedidosStore.crearPedido(payload)

  if (order) {
    // El voucher se sube después, en un segundo paso — así no perdemos
    // el pedido si la imagen falla al subir por algún motivo
    if (voucherFile.value) {
      await pedidosStore.subirVoucher(order.id, voucherFile.value)
    }
    form.value = emptyForm()
    voucherFile.value = null
    close()
  }

  submitting.value = false
}

watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) {
      form.value = emptyForm()
      voucherFile.value = null
    }
  },
)

onMounted(() => {
  if (catalogoStore.products.length === 0) catalogoStore.fetchProducts()
})
</script>
