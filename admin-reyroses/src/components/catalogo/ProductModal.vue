<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['rounded-2xl p-6 md:p-8 w-full max-w-md shadow-2xl max-h-[90vh] overflow-y-auto', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
            {{ isEditing ? 'Editar Variedad' : 'Agregar Producto' }}
          </h2>
          <button @click="close"
            :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <div>
            <label
              :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Categoria</label>
            <select v-model="form.category_id" required
              :class="['w-full px-4 py-3 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none cursor-pointer', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white' : 'bg-gray-100 border border-gray-200 text-gray-900']">
              <option value="" disabled>Selecciona una categoria...</option>
              <option value="1">Rosas</option>
              <option value="2">Garden</option>
              <option value="3">Tinted</option>
            </select>
          </div>

          <div>
            <label
              :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Nombre
              de la variedad</label>
            <input v-model="form.name" type="text" placeholder="Ej: Rosa Explorer" required
              :class="['w-full px-4 py-3 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white placeholder-zinc-600' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']" />
          </div>

          <div>
            <label
              :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
              Fotografia {{ isEditing ? '- Opcional' : '' }}
            </label>
            <input type="file" @change="handleFileUpload" accept="image/webp, image/png, image/jpeg"
              :class="['w-full px-4 py-2 rounded-xl focus:ring-2 focus:ring-emerald-500/50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 cursor-pointer', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-zinc-400' : 'bg-gray-100 border border-gray-200 text-gray-500']" />
          </div>

          <div
            :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
            <label
              :class="['block text-xs font-semibold uppercase tracking-wide mb-3', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Precio
              y Stock por Tallo</label>
            <div class="grid grid-cols-5 gap-3">
              <div v-for="size in ['50', '60', '70', '80', '90']" :key="size" class="text-center flex flex-col gap-2">
                <span :class="['text-[10px] font-bold', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">{{ size
                  }}cm</span>

                <input v-model="form['price_' + size]" type="number" step="0.01" placeholder="S/ Precio"
                  :class="['w-full px-1 py-2 text-xs font-bold rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none text-center', isDarkTheme ? 'text-white bg-[#16161a] border border-[#2a2a32]' : 'text-gray-900 bg-white border border-gray-200']" />

                <input v-model="form['stock_' + size]" type="number" placeholder="Stock"
                  :class="['w-full px-1 py-2 text-xs font-bold rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none text-center', isDarkTheme ? 'text-emerald-400 bg-[#16161a] border border-emerald-900/30' : 'text-emerald-600 bg-white border border-emerald-200']" />
              </div>
            </div>
          </div>

          <div
            :class="['flex justify-end gap-3 mt-8 pt-4 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
            <button type="button" @click="close"
              :class="['px-5 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'text-zinc-400 hover:bg-[#1e1e24]' : 'text-gray-500 hover:bg-gray-100']">
              Cancelar
            </button>
            <button type="submit"
              class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all">
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue'
import { X } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCatalogoStore, emptyProductForm } from '@/stores/catalogo'

const { isDarkTheme } = useTheme()
const catalogoStore = useCatalogoStore()

const props = defineProps({
  modelValue: { type: Boolean, default: false },
  // Si viene un producto, el modal edita; si es null, crea uno nuevo
  product: { type: Object, default: null },
})

const emit = defineEmits(['update:modelValue'])

const isEditing = ref(false)
const form = ref(emptyProductForm())
const imageFile = ref(null)

// Cada vez que se abre el modal, arma el formulario según si es crear o editar
watch(
  () => props.modelValue,
  (isOpen) => {
    if (!isOpen) return

    imageFile.value = null

    if (props.product) {
      isEditing.value = true
      const p = props.product
      form.value = {
        name: p.name,
        category_id: p.category_id,
        price_50: p.price_50, stock_50: p.stock_50 || 0,
        price_60: p.price_60, stock_60: p.stock_60 || 0,
        price_70: p.price_70, stock_70: p.stock_70 || 0,
        price_80: p.price_80, stock_80: p.stock_80 || 0,
        price_90: p.price_90, stock_90: p.stock_90 || 0,
      }
    } else {
      isEditing.value = false
      form.value = emptyProductForm()
    }
  },
)

const handleFileUpload = (event) => {
  imageFile.value = event.target.files[0]
}

const close = () => emit('update:modelValue', false)

const handleSubmit = async () => {
  const editingId = isEditing.value ? props.product.id : null
  const saved = await catalogoStore.saveProduct(form.value, imageFile.value, editingId)
  if (saved) close()
}
</script>
