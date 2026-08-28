import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const useCajaStore = defineStore('caja', () => {
  const movimientos = ref([])
  const arqueos = ref([])
  const saldoActual = ref(0)
  const loading = ref(false)

  async function fetchMovimientos() {
    loading.value = true
    try {
      const response = await api.get('/caja/movimientos')
      movimientos.value = response.data.movimientos
      saldoActual.value = response.data.saldo_actual
    } catch (error) {
      console.error('Error cargando movimientos de caja:', error)
      toast.error('No se pudieron cargar los movimientos de caja')
    } finally {
      loading.value = false
    }
  }

  async function crearMovimiento(data) {
    try {
      await api.post('/caja/movimientos', data)
      toast.success('Movimiento registrado con éxito')
      await fetchMovimientos()
      return true
    } catch (error) {
      console.error('Error al registrar el movimiento:', error)
      if (error.response?.data?.errors) {
        const mensajes = Object.values(error.response.data.errors).flat().join(' | ')
        toast.error('Revisa los datos: ' + mensajes)
      } else {
        toast.error('No se pudo registrar el movimiento')
      }
      return false
    }
  }

  async function fetchArqueos() {
    try {
      const response = await api.get('/caja/arqueos')
      arqueos.value = response.data
    } catch (error) {
      console.error('Error cargando arqueos:', error)
      toast.error('No se pudieron cargar los arqueos')
    }
  }

  async function crearArqueo(data) {
    try {
      await api.post('/caja/arqueos', data)
      toast.success('Arqueo registrado con éxito')
      await fetchArqueos()
      return true
    } catch (error) {
      console.error('Error al registrar el arqueo:', error)
      toast.error('No se pudo registrar el arqueo')
      return false
    }
  }

  return {
    movimientos,
    arqueos,
    saldoActual,
    loading,
    fetchMovimientos,
    crearMovimiento,
    fetchArqueos,
    crearArqueo,
  }
})
