import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const usePedidosStore = defineStore('pedidos', () => {
  const orders = ref([])
  const loading = ref(false)

  const currentPage = ref(1)
  const lastPage = ref(1)
  const from = ref(0)
  const to = ref(0)
  const total = ref(0)
  const totalPendiente = ref(0)
  const lastFilters = ref({})

  /**
   * filters opcional: { tipo_pago: 'Credito', estado_pago: 'Pendiente' } o { status: 'Pendiente' }
   * — se recuerda internamente para que updateStatus/marcarPagado/crearPedido
   * refresquen sin sacarte de la pestaña o el filtro en el que estabas.
   */
  async function fetchOrders(page = 1, filters = {}) {
    loading.value = true
    lastFilters.value = filters
    try {
      const response = await api.get('/orders', { params: { page, ...filters } })
      orders.value = response.data.data
      currentPage.value = response.data.current_page
      lastPage.value = response.data.last_page
      from.value = response.data.from || 0
      to.value = response.data.to || 0
      total.value = response.data.total
      totalPendiente.value = response.data.total_pendiente ?? 0
    } catch (error) {
      console.error('Error cargando pedidos:', error)
      toast.error('No se pudieron cargar los pedidos')
    } finally {
      loading.value = false
    }
  }

  async function updateStatus(id, status) {
    try {
      await api.put(`/orders/${id}/status`, { status })
      toast.success(`Pedido #${id.toString().padStart(4, '0')} marcado como: ${status}`)
      await fetchOrders(currentPage.value, lastFilters.value)
    } catch (error) {
      console.error('Error al actualizar el estado:', error)
      toast.error('Hubo un error al procesar la solicitud.')
    }
  }

  async function marcarPagado(id) {
    try {
      await api.put(`/orders/${id}/marcar-pagado`)
      toast.success(`Pedido #${id.toString().padStart(4, '0')} marcado como pagado`)
      await fetchOrders(currentPage.value, lastFilters.value)
    } catch (error) {
      console.error('Error al marcar como pagado:', error)
      toast.error('Hubo un error al procesar la solicitud.')
    }
  }

  /**
   * Crea un pedido manualmente desde el panel (ventas por teléfono/WhatsApp, crédito, etc.)
   * Devuelve el pedido creado (con su id) para poder subir el voucher justo después.
   */
  async function crearPedido(payload) {
    try {
      const response = await api.post('/orders', payload)
      toast.success('Pedido registrado con éxito')
      await fetchOrders(1, lastFilters.value)
      return response.data.order
    } catch (error) {
      console.error('Error al crear el pedido:', error)
      if (error.response?.data?.errors) {
        const mensajes = Object.values(error.response.data.errors).flat().join(' | ')
        toast.error('Revisa los datos: ' + mensajes)
      } else {
        toast.error('No se pudo registrar el pedido')
      }
      return null
    }
  }

  /**
   * Sube (o reemplaza) el voucher de pago de un pedido ya creado.
   */
  async function subirVoucher(orderId, file) {
    const formData = new FormData()
    formData.append('voucher', file)

    try {
      await api.post(`/orders/${orderId}/voucher`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      })
      toast.success('Voucher adjuntado con éxito')
      await fetchOrders(currentPage.value, lastFilters.value)
      return true
    } catch (error) {
      console.error('Error al subir el voucher:', error)
      toast.error('No se pudo subir el voucher')
      return false
    }
  }

  return {
    orders,
    loading,
    currentPage,
    lastPage,
    from,
    to,
    total,
    totalPendiente,
    fetchOrders,
    updateStatus,
    marcarPagado,
    crearPedido,
    subirVoucher,
  }
})
