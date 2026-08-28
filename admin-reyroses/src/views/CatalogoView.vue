<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center z-10 gap-4', isDarkTheme ? 'bg-ink-900 border-b border-ink-700' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Catálogo</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-ink-600' : 'text-gray-500']">
          {{ filteredProducts.length }} producto{{ filteredProducts.length !== 1 ? 's' : '' }} encontrado{{
            filteredProducts.length !== 1 ? 's' : '' }}
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
      <button @click="changeTab('Todas')" :class="[
        'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg shrink-0',
        activeTab === 'Todas'
          ? isDarkTheme ? 'text-brass-400 bg-ink-950' : 'text-brass-600 bg-gray-100'
          : isDarkTheme ? 'text-ink-600 hover:text-ink-400 hover:bg-ink-800' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
      ]">
        Todas
      </button>
      <button v-for="category in catalogoStore.categories" :key="category.id" @click="changeTab(category.name)" :class="[
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

      <div v-else
        :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-ink-900 border-ink-700' : 'bg-white border-gray-200']">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse min-w-[980px]">
            <thead>
              <tr
                :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-ink-800 text-ink-600 border-b border-ink-700' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                <th class="p-4 font-semibold">Producto</th>
                <th class="p-4 font-semibold">Categoría</th>
                <th class="p-4 font-semibold text-center">Tipo</th>
                <th v-for="tallo in TALLOS" :key="tallo" class="p-3 font-semibold text-center">{{ tallo }}cm</th>
                <th class="p-4 font-semibold text-right">Desde</th>
                <th class="p-4 font-semibold text-center">Acción</th>
              </tr>
            </thead>
            <tbody :class="['text-sm', isDarkTheme ? 'text-ink-300' : 'text-gray-700']">
              <tr v-for="product in pagedProducts" :key="product.id"
                :class="['transition-colors', isDarkTheme ? 'border-b border-ink-700 hover:bg-ink-800' : 'border-b border-gray-100 hover:bg-gray-50']">
                <td class="p-4">
                  <div class="flex items-center gap-3">
                    <div
                      :class="['w-10 h-10 rounded-lg overflow-hidden shrink-0 flex items-center justify-center', isDarkTheme ? 'bg-ink-800' : 'bg-gray-100']">
                      <img v-if="product.image_path" :src="apiUrl + '/storage/' + product.image_path"
                        class="w-full h-full object-cover" />
                      <ImageOff v-else :class="['w-4 h-4', isDarkTheme ? 'text-ink-700' : 'text-gray-300']"
                        :stroke-width="1.5" />
                    </div>
                    <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ product.name
                      }}</span>
                  </div>
                </td>
                <td :class="['p-4', isDarkTheme ? 'text-ink-500' : 'text-gray-500']">{{ product.category ?
                  product.category.name : '—' }}</td>
                <td class="p-4 text-center">
                  <span
                    :class="['px-1.5 py-0.5 rounded text-[9px] font-bold', product.tipo === 'Exportacion' ? 'bg-blue-500/20 text-blue-400' : 'bg-brass-500/20 text-brass-400']">
                    {{ product.tipo === 'Exportacion' ? 'Export.' : 'Nacional' }}
                  </span>
                </td>
                <td v-for="tallo in TALLOS" :key="tallo" class="p-3 text-center">
                  <span
                    :class="['font-mono tabular-nums text-xs font-bold px-1.5 py-0.5 rounded', stockColor(product['stock_' + tallo])]">
                    {{ product['stock_' + tallo] }}
                  </span>
                </td>
                <td
                  :class="['p-4 text-right font-bold font-mono tabular-nums', isDarkTheme ? 'text-white' : 'text-gray-900']">
                  S/ {{ product.price_50 || '0.00' }}
                </td>
                <td class="p-4 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button @click="openEditModal(product)"
                      :class="['p-2 rounded-lg transition-colors', isDarkTheme ? 'bg-ink-800 text-ink-500 hover:bg-ink-700 hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
                      title="Editar">
                      <Pencil class="w-4 h-4" :stroke-width="2" />
                    </button>
                    <button @click="openDeleteConfirm(product)"
                      class="p-2 bg-brick-500/10 text-brick-400 rounded-lg hover:bg-brick-500 hover:text-white transition-all"
                      title="Eliminar">
                      <Trash2 class="w-4 h-4" :stroke-width="2" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="px-4">
          <Pagination :current-page="currentPage" :last-page="lastPage" :from="pageFrom" :to="pageTo"
            :total="filteredProducts.length" @change="(page) => (currentPage = page)" />
        </div>
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
import { ref, computed, watch, onMounted } from 'vue'
import { Search, RefreshCw, Plus, PackageOpen, Tags, ImageOff, Pencil, Trash2 } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useCatalogoStore } from '@/stores/catalogo'
import ProductModal from '@/components/catalogo/ProductModal.vue'
import CategoryManagerModal from '@/components/catalogo/CategoryManagerModal.vue'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'
import Pagination from '@/components/shared/Pagination.vue'

const TALLOS = [50, 60, 70, 80, 90]
const PER_PAGE = 12

const { isDarkTheme } = useTheme()
const catalogoStore = useCatalogoStore()
const apiUrl = import.meta.env.VITE_API_URL

const activeTab = ref('Todas')
const searchQuery = ref('')
const currentPage = ref(1)

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

// Paginación en el navegador — la lista completa ya vive en el store
// (la necesitan los selects de Compras/Pedidos/Inventario tal cual)
const lastPage = computed(() => Math.max(1, Math.ceil(filteredProducts.value.length / PER_PAGE)))
const pagedProducts = computed(() => {
  const start = (currentPage.value - 1) * PER_PAGE
  return filteredProducts.value.slice(start, start + PER_PAGE)
})
const pageFrom = computed(() => (filteredProducts.value.length === 0 ? 0 : (currentPage.value - 1) * PER_PAGE + 1))
const pageTo = computed(() => Math.min(currentPage.value * PER_PAGE, filteredProducts.value.length))

const changeTab = (tab) => {
  activeTab.value = tab
  currentPage.value = 1
}

// Si el resultado de la búsqueda achica la lista, no te quedes en una página vacía
watch([searchQuery, filteredProducts], () => {
  if (currentPage.value > lastPage.value) currentPage.value = lastPage.value
})

const stockColor = (stock) => {
  const n = Number(stock) || 0
  if (n === 0) return 'text-brick-500'
  if (n <= 5) return 'text-amber-500'
  return isDarkTheme.value ? 'text-pine-400' : 'text-pine-600'
}

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
