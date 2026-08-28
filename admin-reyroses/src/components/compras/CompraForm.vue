<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-3xl shadow-2xl max-h-[92vh] overflow-y-auto', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Nueva Compra</h2>
          <button @click="close"
            :class="['p-1', isDarkTheme ? 'text-ink-600 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Datos generales -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="col-span-2 md:col-span-1">
              <label :class="labelClass">Fecha</label>
              <input v-model="form.fecha" type="date" required :class="inputClass" />
            </div>
            <div class="col-span-2 md:col-span-1">
              <label :class="labelClass">Proveedor</label>
              <input v-model="form.proveedor" type="text" placeholder="Opcional" :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Tipo de cambio (USD→PEN)</label>
              <input v-model="form.tipo_cambio" type="number" step="0.0001" min="0" placeholder="3.75" required
                :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Comisión Western</label>
              <input v-model="form.comision_western" type="number" step="0.01" min="0" placeholder="Aún no confirmada"
                :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Pago a trabajadores (S/)</label>
              <input v-model="form.pago_trabajadores" type="number" step="0.01" min="0" :class="inputClass" />
            </div>
            <div>
              <label :class="labelClass">Saldo de inversión usado (S/)</label>
              <input v-model="form.saldo_inversion_usado" type="number" step="0.01" min="0"
                :max="inversionStore.saldoActual" :class="inputClass" />
              <p :class="['text-[11px] mt-1', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
                Disponible: S/ {{ inversionStore.saldoActual.toFixed(2) }}
              </p>
            </div>
          </div>

          <!-- Tabacos -->
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3
                :class="['text-xs font-semibold uppercase tracking-widest', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
                Tabacos ({{ form.tabacos.length }})
              </h3>
              <button type="button" @click="addTabaco"
                class="text-xs font-semibold text-pine-500 hover:text-pine-400 flex items-center gap-1">
                <Plus class="w-3.5 h-3.5" :stroke-width="2.5" />
                Agregar tabaco
              </button>
            </div>

            <div class="space-y-4">
              <div v-for="(tabaco, index) in form.tabacos" :key="index"
                :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
                <div class="flex items-center justify-between mb-3">
                  <span :class="['text-xs font-bold uppercase', isDarkTheme ? 'text-ink-400' : 'text-gray-500']">Tabaco
                    #{{ index + 1 }}</span>
                  <button v-if="form.tabacos.length > 1" type="button" @click="removeTabaco(index)"
                    class="text-brick-400 hover:text-brick-500">
                    <Trash2 class="w-4 h-4" :stroke-width="2" />
                  </button>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                  <div>
                    <label :class="labelClass">Variedad</label>
                    <select v-model="tabaco.product_id" required :class="inputClass">
                      <option value="" disabled>Selecciona...</option>
                      <option v-for="product in catalogoStore.products" :key="product.id" :value="product.id">
                        {{ product.name }} — {{ product.tipo === 'Exportacion' ? 'Exportación' : 'Nacional' }}
                      </option>
                    </select>
                  </div>
                  <div>
                    <label :class="labelClass">Costo del tabaco (USD)</label>
                    <input v-model="tabaco.costo_tabaco_usd" type="number" step="0.01" min="0" required
                      :class="inputClass" />
                  </div>
                </div>

                <label :class="labelClass">Paquetes por tallo</label>
                <div class="grid grid-cols-5 gap-2">
                  <div v-for="tallo in TALLOS" :key="tallo" class="text-center">
                    <span
                      :class="['text-[10px] font-bold block mb-1', isDarkTheme ? 'text-ink-700' : 'text-gray-400']">{{
                        tallo }}cm</span>
                    <input v-model="tabaco.desglose[tallo]" type="number" min="0"
                      :class="['w-full px-1 py-2 text-xs font-bold rounded-lg focus:ring-2 focus:ring-brass-500/50 outline-none text-center', isDarkTheme ? 'text-white bg-ink-900 border border-ink-700' : 'text-gray-900 bg-white border border-gray-200']" />
                  </div>
                </div>
                <p
                  :class="['text-xs font-semibold mt-2', totalPaquetes(tabaco) === 14 ? 'text-pine-500' : 'text-amber-500']">
                  Total: {{ totalPaquetes(tabaco) }} / 14 paquetes
                </p>
              </div>
            </div>
          </div>

          <!-- Resumen financiero en vivo -->
          <div
            :class="['p-4 rounded-xl border space-y-2', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-pine-50 border-pine-100']">
            <h3
              :class="['text-xs font-semibold uppercase tracking-widest mb-2', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
              Proyección
            </h3>
            <ResumenRow label="Subtotal tabacos (USD)" :value="'US$ ' + resumen.subtotalUsd.toFixed(2)"
              :dark="isDarkTheme" />
            <ResumenRow label="Subtotal en soles" :value="'S/ ' + resumen.subtotalPen.toFixed(2)" :dark="isDarkTheme" />
            <ResumenRow label="Comisión tarjeta (5%)" :value="'S/ ' + resumen.comisionTarjeta.toFixed(2)"
              :dark="isDarkTheme" />
            <ResumenRow label="Costo total" :value="'S/ ' + resumen.costoTotal.toFixed(2)" :dark="isDarkTheme" bold />
            <ResumenRow label="Ingreso proyectado (venta)" :value="'S/ ' + resumen.ingresoProyectado.toFixed(2)"
              :dark="isDarkTheme" />
            <ResumenRow label="Ganancia estimada" :value="'S/ ' + resumen.gananciaEstimada.toFixed(2)"
              :dark="isDarkTheme" bold
              :class-override="resumen.gananciaEstimada >= 0 ? 'text-pine-500' : 'text-red-500'" />
          </div>

          <div :class="['flex justify-end gap-3 pt-4 border-t', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
            <button type="button" @click="close"
              :class="['px-5 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'text-ink-400 hover:bg-ink-800' : 'text-gray-500 hover:bg-gray-100']">
              Cancelar
            </button>
            <button type="submit"
              class="px-5 py-3 bg-brass-500 hover:bg-brass-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all">
              Registrar compra
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, computed, watch, h, onMounted } from 'vue'
import { X, Plus, Trash2 } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useComprasStore, emptyCompraForm, emptyTabaco, TALLOS } from '@/stores/compras'
import { useCatalogoStore } from '@/stores/catalogo'
import { useInversionStore } from '@/stores/inversion'

const { isDarkTheme } = useTheme()
const comprasStore = useComprasStore()
const catalogoStore = useCatalogoStore()
const inversionStore = useInversionStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const form = ref(emptyCompraForm())

const labelClass = 'block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600'
const inputClass = computed(() => [
  'w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brass-500/50 focus:border-brass-500/50 outline-none',
  isDarkTheme.value ? 'bg-ink-900 border border-ink-700 text-white' : 'bg-gray-100 border border-gray-200 text-gray-900',
])

// Componente inline chico para no repetir el markup de cada fila del resumen
const ResumenRow = (props) => h(
  'div',
  { class: 'flex justify-between text-sm' },
  [
    h('span', { class: props.dark ? 'text-ink-400' : 'text-gray-600' }, props.label),
    h('span', { class: ['font-mono', props.bold ? 'font-bold' : 'font-medium', props.classOverride || (props.dark ? 'text-white' : 'text-gray-900')] }, props.value),
  ],
)
ResumenRow.props = ['label', 'value', 'dark', 'bold', 'classOverride']

const totalPaquetes = (tabaco) => TALLOS.reduce((sum, t) => sum + (Number(tabaco.desglose[t]) || 0), 0)

const resumen = computed(() => {
  let subtotalUsd = 0
  let ingresoProyectado = 0

  for (const tabaco of form.value.tabacos) {
    subtotalUsd += Number(tabaco.costo_tabaco_usd) || 0

    const product = catalogoStore.products.find((p) => p.id === Number(tabaco.product_id))
    if (!product) continue

    for (const tallo of TALLOS) {
      const cantidad = Number(tabaco.desglose[tallo]) || 0
      const precio = Number(product['price_' + tallo]) || 0
      ingresoProyectado += cantidad * precio
    }
  }

  const tipoCambio = Number(form.value.tipo_cambio) || 0
  const subtotalPen = subtotalUsd * tipoCambio
  const comisionTarjeta = subtotalPen * 0.05
  const comisionWestern = Number(form.value.comision_western) || 0
  const pagoTrabajadores = Number(form.value.pago_trabajadores) || 0
  const costoTotal = subtotalPen + comisionTarjeta + comisionWestern + pagoTrabajadores

  return {
    subtotalUsd,
    subtotalPen,
    comisionTarjeta,
    costoTotal,
    ingresoProyectado,
    gananciaEstimada: ingresoProyectado - costoTotal,
  }
})

const addTabaco = () => form.value.tabacos.push(emptyTabaco())
const removeTabaco = (index) => form.value.tabacos.splice(index, 1)

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const saved = await comprasStore.createCompra(form.value)
  if (saved) {
    form.value = emptyCompraForm()
    close()
  }
}

watch(
  () => props.modelValue,
  (isOpen) => {
    if (isOpen) form.value = emptyCompraForm()
  },
)

onMounted(() => {
  if (catalogoStore.products.length === 0) catalogoStore.fetchProducts()
  inversionStore.fetchMovimientos()
})
</script>
