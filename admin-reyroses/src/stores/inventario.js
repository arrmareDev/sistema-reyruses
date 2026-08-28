import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const TIPOS_AJUSTE = ['Merma', 'Daño', 'Conteo físico', 'Otro']

export const useInventarioStore = defineStore('inventario', () => {
  const movimientos = ref([])
  const loading = ref(false)

  const currentPage = ref(1)
  const lastPage = ref(1)
  const from = ref(0)
  const to = ref(0)
  const total = ref(0)

  async function fetchMovimientos(page = 1) {
    loading.value = true
    try {
      const response = await api.get('/inventario', { params: { page } })
      movimientos.value = response.data.data
      currentPage.value = response.data.current_page
      lastPage.value = response.data.last_page
      from.value = response.data.from || 0
      to.value = response.data.to || 0
      total.value = response.data.total
    } catch (error) {
      console.error('Error cargando movimientos de inventario:', error)
      toast.error('No se pudieron cargar los ajustes de inventario')
    } finally {
      loading.value = false
    }
  }

  async function crearMovimiento(data) {
    try {
      await api.post('/inventario', data)
      toast.success('Ajuste de inventario registrado con éxito')
      await fetchMovimientos(1)
      return true
    } catch (error) {
      console.error('Error al registrar el ajuste:', error)
      toast.error(error.response?.data?.message || 'No se pudo registrar el ajuste')
      return false
    }
  }

  return {
    movimientos,
    loading,
    currentPage,
    lastPage,
    from,
    to,
    total,
    fetchMovimientos,
    crearMovimiento,
  }
})
