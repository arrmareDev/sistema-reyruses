<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div v-if="compra"
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-2xl shadow-2xl max-h-[92vh] overflow-y-auto', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <div>
            <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
              Compra #{{ compra.id.toString().padStart(4, '0') }}
            </h2>
            <p :class="['text-xs mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
              {{ new Date(compra.fecha).toLocaleDateString() }} · {{ compra.proveedor || 'Sin proveedor' }}
            </p>
          </div>
          <button @click="$emit('update:modelValue', false)"
            :class="['p-1', isDarkTheme ? 'text-ink-600 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <span :class="[
          'inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide mb-5',
          compra.estado === 'Recibida' ? 'bg-pine-500/20 text-pine-400' : 'bg-amber-500/20 text-amber-400',
        ]">
          {{ compra.estado }}
        </span>

        <div class="space-y-4 mb-6">
          <div v-for="tabaco in compra.tabacos" :key="tabaco.id"
            :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
            <div class="flex justify-between items-center mb-2">
              <span :class="['font-semibold text-sm', isDarkTheme ? 'text-white' : 'text-gray-900']">
                {{ tabaco.product?.name }}
                <span
                  :class="['text-[10px] font-bold px-1.5 py-0.5 rounded ml-1', tabaco.product?.tipo === 'Exportacion' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400']">
                  {{ tabaco.product?.tipo === 'Exportacion' ? 'Exportación' : 'Nacional' }}
                </span>
              </span>
              <span :class="['text-xs', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">US$ {{ tabaco.costo_tabaco_usd
                }}</span>
            </div>
            <div class="flex flex-wrap gap-2">
              <span v-for="item in tabaco.desglose" :key="item.id"
                :class="['text-[11px] font-semibold px-2 py-1 rounded-md', isDarkTheme ? 'bg-ink-900 text-pine-400' : 'bg-white text-pine-600 border border-pine-100']">
                {{ item.tallo }}cm × {{ item.cantidad_paquetes }}
              </span>
            </div>
          </div>
        </div>

        <div
          :class="['p-4 rounded-xl border space-y-2 mb-6', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-pine-50 border-pine-100']">
          <ResumenRow label="Subtotal tabacos (USD)" :value="'US$ ' + compra.subtotal_tabacos_usd.toFixed(2)" />
          <ResumenRow label="Subtotal en soles" :value="'S/ ' + compra.subtotal_tabacos_pen.toFixed(2)" />
          <ResumenRow label="Comisión tarjeta (5%)" :value="'S/ ' + compra.comision_tarjeta.toFixed(2)" />
          <ResumenRow label="Comisión Western"
            :value="compra.comision_western ? 'S/ ' + Number(compra.comision_western).toFixed(2) : 'Pendiente de confirmar'" />
          <ResumenRow label="Pago trabajadores" :value="'S/ ' + Number(compra.pago_trabajadores).toFixed(2)" />
          <ResumenRow label="Costo total" :value="'S/ ' + compra.costo_total.toFixed(2)" bold />
          <ResumenRow label="Ingreso proyectado" :value="'S/ ' + compra.ingreso_proyectado.toFixed(2)" />
          <ResumenRow label="Ganancia estimada" :value="'S/ ' + compra.ganancia_estimada.toFixed(2)" bold
            :value-class="compra.ganancia_estimada >= 0 ? 'text-pine-500' : 'text-red-500'" />
        </div>

        <!-- Editar comisión Western cuando la agencia la confirma -->
        <div v-if="!compra.comision_western"
          :class="['p-4 rounded-xl border mb-6 flex items-end gap-3', isDarkTheme ? 'bg-ink-800 border-ink-700' : 'bg-gray-50 border-gray-200']">
          <div class="flex-1">
            <label class="block text-[11px] font-semibold uppercase tracking-wide mb-1.5 text-ink-600">
              Confirmar comisión Western
            </label>
            <input v-model="comisionWesternInput" type="number" step="0.01" min="0"
              placeholder="Monto que descontó la agencia"
              :class="['w-full px-3 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none', isDarkTheme ? 'bg-ink-900 border border-ink-700 text-white' : 'bg-white border border-gray-200 text-gray-900']" />
          </div>
          <button @click="guardarComisionWestern"
            class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all">
            Guardar
          </button>
        </div>

        <div class="flex justify-end gap-3">
          <button v-if="compra.estado !== 'Recibida'" @click="$emit('recibir', compra.id)"
            class="px-5 py-3 bg-brass-500 hover:bg-brass-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
            <PackageCheck class="w-4 h-4" :stroke-width="2" />
            Marcar como recibida
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { X, PackageCheck } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useComprasStore } from '@/stores/compras'
import ResumenRow from '@/components/shared/ResumenRow.vue'

const { isDarkTheme } = useTheme()
const comprasStore = useComprasStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  compra: { type: Object, default: null },
})

defineEmits(['update:modelValue', 'recibir'])

const comisionWesternInput = ref('')

watch(
  () => props.compra,
  () => { comisionWesternInput.value = '' },
)

const guardarComisionWestern = async () => {
  if (!comisionWesternInput.value || !props.compra) return
  await comprasStore.updateCompra(props.compra.id, { comision_western: comisionWesternInput.value })
}
</script>
