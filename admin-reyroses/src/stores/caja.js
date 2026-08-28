import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const useCajaStore = defineStore('caja', () => {
  const movimientos = ref([])
  const saldoActual = ref(0)
  const totalIngresos = ref(0)
  const totalEgresos = ref(0)
  const movLoading = ref(false)
  const movCurrentPage = ref(1)
  const movLastPage = ref(1)
  const movFrom = ref(0)
  const movTo = ref(0)
  const movTotal = ref(0)

  const arqueos = ref([])
  const arqLoading = ref(false)
  const arqCurrentPage = ref(1)
  const arqLastPage = ref(1)
  const arqFrom = ref(0)
  const arqTo = ref(0)
  const arqTotal = ref(0)

  async function fetchMovimientos(page = 1) {
    movLoading.value = true
    try {
      const response = await api.get('/caja/movimientos', { params: { page } })
      movimientos.value = response.data.movimientos.data
      movCurrentPage.value = response.data.movimientos.current_page
      movLastPage.value = response.data.movimientos.last_page
      movFrom.value = response.data.movimientos.from || 0
      movTo.value = response.data.movimientos.to || 0
      movTotal.value = response.data.movimientos.total
      saldoActual.value = response.data.saldo_actual
      totalIngresos.value = response.data.total_ingresos
      totalEgresos.value = response.data.total_egresos
    } catch (error) {
      console.error('Error cargando movimientos de caja:', error)
      toast.error('No se pudieron cargar los movimientos de caja')
    } finally {
      movLoading.value = false
    }
  }

  async function crearMovimiento(data) {
    try {
      await api.post('/caja/movimientos', data)
      toast.success('Movimiento registrado con éxito')
      await fetchMovimientos(1)
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

  async function fetchArqueos(page = 1) {
    arqLoading.value = true
    try {
      const response = await api.get('/caja/arqueos', { params: { page } })
      arqueos.value = response.data.data
      arqCurrentPage.value = response.data.current_page
      arqLastPage.value = response.data.last_page
      arqFrom.value = response.data.from || 0
      arqTo.value = response.data.to || 0
      arqTotal.value = response.data.total
    } catch (error) {
      console.error('Error cargando arqueos:', error)
      toast.error('No se pudieron cargar los arqueos')
    } finally {
      arqLoading.value = false
    }
  }

  async function crearArqueo(data) {
    try {
      await api.post('/caja/arqueos', data)
      toast.success('Arqueo registrado con éxito')
      await fetchArqueos(1)
      return true
    } catch (error) {
      console.error('Error al registrar el arqueo:', error)
      toast.error('No se pudo registrar el arqueo')
      return false
    }
  }

  return {
    movimientos,
    saldoActual,
    totalIngresos,
    totalEgresos,
    movLoading,
    movCurrentPage,
    movLastPage,
    movFrom,
    movTo,
    movTotal,
    arqueos,
    arqLoading,
    arqCurrentPage,
    arqLastPage,
    arqFrom,
    arqTo,
    arqTotal,
    fetchMovimientos,
    crearMovimiento,
    fetchArqueos,
    crearArqueo,
  }
})
