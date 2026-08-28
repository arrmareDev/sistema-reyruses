<template>
  <Teleport to="body">
    <div v-if="modelValue"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
      <div
        :class="['w-full max-w-md rounded-2xl shadow-2xl max-h-[85vh] flex flex-col', isDarkTheme ? 'bg-ink-900 border border-ink-700' : 'bg-white border border-gray-200']">
        <div
          :class="['flex items-center justify-between px-6 py-5 border-b shrink-0', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <div class="flex items-center gap-3">
            <div :class="['p-2 rounded-xl', isDarkTheme ? 'bg-brass-500/10' : 'bg-brass-500/10']">
              <Tags class="w-5 h-5 text-brass-500" :stroke-width="2" />
            </div>
            <div>
              <h2 :class="['text-lg font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Categorías</h2>
              <p :class="['text-xs', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">{{ catalogoStore.categories.length
                }} en total</p>
            </div>
          </div>
          <button @click="close"
            :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white hover:bg-ink-800' : 'text-gray-400 hover:text-gray-900 hover:bg-gray-100']">
            <X class="w-5 h-5" :stroke-width="2" />
          </button>
        </div>

        <!-- Crear nueva -->
        <form @submit.prevent="handleCreate"
          :class="['flex gap-2 px-6 py-4 border-b shrink-0', isDarkTheme ? 'border-ink-700' : 'border-gray-200']">
          <input v-model="newCategoryName" type="text" placeholder="Nombre de la categoría nueva..."
            :class="['flex-1 px-3.5 py-2.5 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none', isDarkTheme ? 'bg-ink-800 border border-ink-700 text-white placeholder-ink-600' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']" />
          <button type="submit" :disabled="!newCategoryName.trim()"
            class="px-4 py-2.5 bg-brass-500 hover:bg-brass-600 disabled:opacity-40 disabled:pointer-events-none text-white font-semibold text-sm rounded-xl transition-all flex items-center gap-1.5 shrink-0">
            <Plus class="w-4 h-4" :stroke-width="2.5" />
            Crear
          </button>
        </form>

        <!-- Lista -->
        <div class="flex-1 overflow-y-auto px-4 py-3 space-y-1.5">
          <div v-if="catalogoStore.categories.length === 0" class="text-center py-10">
            <p :class="['text-sm', isDarkTheme ? 'text-ink-600' : 'text-gray-400']">Todavía no hay categorías. Crea la
              primera arriba.</p>
          </div>

          <div v-for="category in catalogoStore.categories" :key="category.id"
            :class="['flex items-center gap-2 px-3 py-2.5 rounded-xl transition-colors', isDarkTheme ? 'hover:bg-ink-800' : 'hover:bg-gray-50']">
            <template v-if="editingId === category.id">
              <input v-model="editingName" type="text" autofocus @keyup.enter="handleUpdate(category.id)"
                @keyup.esc="cancelEdit"
                :class="['flex-1 px-3 py-1.5 rounded-lg text-sm font-medium focus:ring-2 focus:ring-brass-500/50 outline-none', isDarkTheme ? 'bg-ink-950 border border-ink-700 text-white' : 'bg-white border border-gray-300 text-gray-900']" />
              <button @click="handleUpdate(category.id)"
                class="p-1.5 rounded-lg text-pine-500 hover:bg-pine-500/10 transition-colors">
                <Check class="w-4 h-4" :stroke-width="2.5" />
              </button>
              <button @click="cancelEdit"
                :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white' : 'text-gray-400 hover:text-gray-700']">
                <X class="w-4 h-4" :stroke-width="2" />
              </button>
            </template>

            <template v-else>
              <span :class="['flex-1 text-sm font-medium', isDarkTheme ? 'text-white' : 'text-gray-900']">{{
                category.name }}</span>
              <span
                :class="['font-mono tabular-nums text-[11px] font-semibold px-2 py-0.5 rounded-md', isDarkTheme ? 'bg-ink-800 text-ink-600' : 'bg-gray-100 text-gray-400']">
                {{ category.products_count }} producto{{ category.products_count !== 1 ? 's' : '' }}
              </span>
              <button @click="startEdit(category)"
                :class="['p-1.5 rounded-lg transition-colors', isDarkTheme ? 'text-ink-600 hover:text-white hover:bg-ink-800' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-100']">
                <Pencil class="w-3.5 h-3.5" :stroke-width="2" />
              </button>
              <button @click="handleDelete(category)" :disabled="category.products_count > 0"
                :title="category.products_count > 0 ? 'No se puede eliminar: tiene productos asignados' : 'Eliminar categoría'"
                class="p-1.5 rounded-lg text-brick-500 hover:bg-brick-500/10 disabled:opacity-30 disabled:pointer-events-none transition-colors">
                <Trash2 class="w-3.5 h-3.5" :stroke-width="2" />
              </button>
            </template>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal v-model="showDeleteConfirm" title="Eliminar categoría"
      :message="`¿Eliminar &quot;${categoryToDelete?.name}&quot;? Esta acción no se puede deshacer.`"
      confirm-label="Sí, eliminar" danger-variant @confirm="executeDelete" @cancel="showDeleteConfirm = false" />
  </Teleport>
</template>

<script setup>
import { ref } from 'vue'
import { Tags, X, Plus, Pencil, Check, Trash2 } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCatalogoStore } from '@/stores/catalogo'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'

const { isDarkTheme } = useTheme()
const catalogoStore = useCatalogoStore()

defineProps({
  modelValue: { type: Boolean, default: false },
})
const emit = defineEmits(['update:modelValue'])

const close = () => emit('update:modelValue', false)

const newCategoryName = ref('')
const handleCreate = async () => {
  const saved = await catalogoStore.createCategory(newCategoryName.value.trim())
  if (saved) newCategoryName.value = ''
}

const editingId = ref(null)
const editingName = ref('')

const startEdit = (category) => {
  editingId.value = category.id
  editingName.value = category.name
}
const cancelEdit = () => {
  editingId.value = null
  editingName.value = ''
}
const handleUpdate = async (id) => {
  if (!editingName.value.trim()) return
  const saved = await catalogoStore.updateCategory(id, editingName.value.trim())
  if (saved) cancelEdit()
}

const showDeleteConfirm = ref(false)
const categoryToDelete = ref(null)
const handleDelete = (category) => {
  categoryToDelete.value = category
  showDeleteConfirm.value = true
}
const executeDelete = async () => {
  if (categoryToDelete.value) await catalogoStore.deleteCategory(categoryToDelete.value.id)
  showDeleteConfirm.value = false
  categoryToDelete.value = null
}
</script>
