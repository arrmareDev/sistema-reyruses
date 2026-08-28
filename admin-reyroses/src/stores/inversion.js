import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const useInversionStore = defineStore('inversion', () => {
  const movimientos = ref([])
  const saldoActual = ref(0)
  const loading = ref(false)

  async function fetchMovimientos() {
    loading.value = true
    try {
      const response = await api.get('/inversion')
      movimientos.value = response.data.movimientos
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
      await fetchMovimientos()
      return true
    } catch (error) {
      console.error('Error al registrar el depósito:', error)
      toast.error('No se pudo registrar el depósito')
      return false
    }
  }

  return { movimientos, saldoActual, loading, fetchMovimientos, depositar }
})
