import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export function emptyProductForm() {
  return {
    name: '',
    category_id: '',
    tipo: 'Nacional',
    price_50: '',
    stock_50: 0,
    price_60: '',
    stock_60: 0,
    price_70: '',
    stock_70: 0,
    price_80: '',
    stock_80: 0,
    price_90: '',
    stock_90: 0,
  }
}

export const useCatalogoStore = defineStore('catalogo', () => {
  const products = ref([])
  const categories = ref([])
  const loading = ref(false)

  async function fetchProducts() {
    loading.value = true
    try {
      const response = await api.get('/products')
      products.value = response.data
    } catch (error) {
      console.error('Error conectando con la base de datos:', error)
      toast.error('No se pudo cargar el catálogo')
    } finally {
      loading.value = false
    }
  }

  async function fetchCategories() {
    try {
      const response = await api.get('/categories')
      categories.value = response.data
    } catch (error) {
      console.error('Error cargando categorías:', error)
      toast.error('No se pudieron cargar las categorías')
    }
  }

  async function createCategory(name) {
    try {
      await api.post('/categories', { name })
      toast.success('Categoría creada con éxito')
      await fetchCategories()
      return true
    } catch (error) {
      console.error('Error al crear categoría:', error)
      toast.error(error.response?.data?.message || 'No se pudo crear la categoría')
      return false
    }
  }

  async function updateCategory(id, name) {
    try {
      await api.put(`/categories/${id}`, { name })
      toast.success('Categoría actualizada')
      await fetchCategories()
      return true
    } catch (error) {
      console.error('Error al actualizar categoría:', error)
      toast.error(error.response?.data?.message || 'No se pudo actualizar la categoría')
      return false
    }
  }

  async function deleteCategory(id) {
    try {
      await api.delete(`/categories/${id}`)
      toast.success('Categoría eliminada')
      await fetchCategories()
      return true
    } catch (error) {
      console.error('Error al eliminar categoría:', error)
      toast.error(error.response?.data?.message || 'No se pudo eliminar la categoría')
      return false
    }
  }

  /**
   * Crea o actualiza un producto. Devuelve true si se guardó bien
   * (para que el componente sepa si cerrar el modal o dejarlo abierto).
   */
  async function saveProduct(formPayload, imageFile, editingId) {
    const formData = new FormData()
    Object.entries(formPayload).forEach(([key, value]) => formData.append(key, value))
    if (imageFile) formData.append('image', imageFile)

    try {
      if (editingId) {
        formData.append('_method', 'PUT')
        await api.post(`/products/${editingId}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        })
        toast.success('Variedad actualizada correctamente')
      } else {
        await api.post('/products', formData, {
          headers: { 'Content-Type': 'multipart/form-data' },
        })
        toast.success('Nueva variedad agregada al catálogo')
      }

      await fetchProducts()
      return true
    } catch (error) {
      console.error('Error al guardar:', error)
      if (error.response?.data?.errors) {
        const mensajes = Object.values(error.response.data.errors).flat().join(' | ')
        toast.error('Revisa los datos: ' + mensajes)
      } else {
        toast.error('Ocurrió un error inesperado al guardar.')
      }
      return false
    }
  }

  async function deleteProduct(product) {
    try {
      await api.delete(`/products/${product.id}`)
      toast.success(`La variedad "${product.name}" fue eliminada`)
      await fetchProducts()
    } catch (error) {
      console.error('Error al eliminar:', error)
      toast.error('No se pudo eliminar el producto')
    }
  }

  return {
    products,
    categories,
    loading,
    fetchProducts,
    fetchCategories,
    createCategory,
    updateCategory,
    deleteCategory,
    saveProduct,
    deleteProduct,
  }
})
