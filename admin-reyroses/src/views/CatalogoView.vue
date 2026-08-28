<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center z-10 gap-4', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Inventario</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          {{ filteredProducts.length }} productos encontrados
        </p>
      </div>

      <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative flex-grow md:flex-grow-0">
          <input v-model="searchQuery" type="text" placeholder="Buscar productos..."
            :class="['w-full pl-10 pr-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brass-500/50 focus:border-brass-500/50 md:w-64 transition-all', isDarkTheme ? 'bg-ink-800 border border-ink-700 text-white placeholder-ink-600' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']" />
          <Search :class="['absolute left-3 top-2.5 w-5 h-5', isDarkTheme ? 'text-ink-600' : 'text-gray-400']"
            :stroke-width="2" />
        </div>

        <button @click="showCategoryManager = true"
          :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-ink-800 text-ink-600 hover:bg-ink-700 hover:text-white border border-ink-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
          title="Gestionar categorías">
          <Tags class="w-5 h-5" :stroke-width="2" />
        </button>

        <button @click="catalogoStore.fetchProducts"
          :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-ink-800 text-ink-600 hover:bg-ink-700 hover:text-white border border-ink-700' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']">
          <RefreshCw class="w-5 h-5" :stroke-width="2" />
        </button>

        <button @click="openCreateModal"
          class="bg-brass-500 hover:bg-brass-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-brass-500/20 transition-all flex items-center gap-2">
          <Plus class="w-4 h-4" :stroke-width="2" />
          Nuevo
        </button>
      </div>
    </header>

    <div
      :class="['px-4 md:px-8 pt-4 flex gap-1 overflow-x-auto', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <button @click="activeTab = 'Todas'" :class="[
        'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg shrink-0',
        activeTab === 'Todas'
          ? isDarkTheme ? 'text-brass-400 bg-ink-950' : 'text-brass-600 bg-gray-100'
          : isDarkTheme ? 'text-ink-600 hover:text-ink-400 hover:bg-ink-800' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
      ]">
        Todas
      </button>
      <button v-for="category in catalogoStore.categories" :key="category.id" @click="activeTab = category.name" :class="[
        'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg shrink-0',
        activeTab === category.name
          ? isDarkTheme ? 'text-brass-400 bg-ink-950' : 'text-brass-600 bg-gray-100'
          : isDarkTheme ? 'text-ink-600 hover:text-ink-400 hover:bg-ink-800' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
      ]">
        {{ category.name }}
      </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div v-if="filteredProducts.length === 0" class="text-center py-20">
        <div
          :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
          <PackageOpen :class="['w-8 h-8', isDarkTheme ? 'text-ink-600' : 'text-gray-400']" :stroke-width="1.5" />
        </div>
        <p :class="['text-base', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">No se encontraron productos</p>
        <p :class="['text-sm mt-1', isDarkTheme ? 'text-ink-700' : 'text-gray-400']">Intenta con otra búsqueda o
          categoría</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" @edit="openEditModal"
          @delete="openDeleteConfirm" />
      </div>
    </div>

    <ProductModal v-model="showModal" :product="editingProduct" @manage-categories="showCategoryManager = true" />

    <CategoryManagerModal v-model="showCategoryManager" />

    <ConfirmModal v-model="showDeleteConfirm" title="Eliminar Variedad"
      :message="`¿Estás seguro de eliminar &quot;${productToDelete?.name}&quot;? No podrás recuperarla.`"
      confirm-label="Sí, Eliminar" danger-variant @confirm="executeDelete" @cancel="showDeleteConfirm = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Search, RefreshCw, Plus, PackageOpen, Tags } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCatalogoStore } from '@/stores/catalogo'
import ProductCard from '@/components/catalogo/ProductCard.vue'
import ProductModal from '@/components/catalogo/ProductModal.vue'
import CategoryManagerModal from '@/components/catalogo/CategoryManagerModal.vue'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'

const { isDarkTheme } = useTheme()
const catalogoStore = useCatalogoStore()

const activeTab = ref('Todas')
const searchQuery = ref('')

const showModal = ref(false)
const editingProduct = ref(null)
const showCategoryManager = ref(false)

const showDeleteConfirm = ref(false)
const productToDelete = ref(null)

const filteredProducts = computed(() => {
  let result = catalogoStore.products
  if (activeTab.value !== 'Todas') {
    result = result.filter((p) => p.category && p.category.name === activeTab.value)
  }
  if (searchQuery.value.trim() !== '') {
    const query = searchQuery.value.toLowerCase()
    result = result.filter((p) => p.name.toLowerCase().includes(query))
  }
  return result
})

const openCreateModal = () => {
  editingProduct.value = null
  showModal.value = true
}

const openEditModal = (product) => {
  editingProduct.value = product
  showModal.value = true
}

const openDeleteConfirm = (product) => {
  productToDelete.value = product
  showDeleteConfirm.value = true
}

const executeDelete = async () => {
  if (productToDelete.value) {
    await catalogoStore.deleteProduct(productToDelete.value)
  }
  showDeleteConfirm.value = false
  productToDelete.value = null
}

onMounted(() => {
  catalogoStore.fetchProducts()
  catalogoStore.fetchCategories()
})
</script>
