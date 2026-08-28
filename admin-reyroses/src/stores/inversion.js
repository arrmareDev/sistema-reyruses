import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const useInversionStore = defineStore('inversion', () => {
  const movimientos = ref([])
  const saldoActual = ref(0)
  const loading = ref(false)
  const currentPage = ref(1)
  const lastPage = ref(1)
  const from = ref(0)
  const to = ref(0)
  const total = ref(0)

  async function fetchMovimientos(page = 1) {
    loading.value = true
    try {
      const response = await api.get('/inversion', { params: { page } })
      movimientos.value = response.data.movimientos.data
      currentPage.value = response.data.movimientos.current_page
      lastPage.value = response.data.movimientos.last_page
      from.value = response.data.movimientos.from || 0
      to.value = response.data.movimientos.to || 0
      total.value = response.data.movimientos.total
      saldoActual.value = response.data.saldo_actual
    } catch (error) {
      console.error('Error cargando movimientos de inversión:', error)
      toast.error('No se pudieron cargar los movimientos del fondo')
    } finally {
      loading.value = false
    }
  }

  async function depositar(data) {
    try {
      await api.post('/inversion', data)
      toast.success('Depósito registrado con éxito')
      await fetchMovimientos(1)
      return true
    } catch (error) {
      console.error('Error al registrar el depósito:', error)
      toast.error('No se pudo registrar el depósito')
      return false
    }
  }

  return {
    movimientos,
    saldoActual,
    loading,
    currentPage,
    lastPage,
    from,
    to,
    total,
    fetchMovimientos,
    depositar,
  }
})
