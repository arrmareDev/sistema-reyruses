import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const usePedidosStore = defineStore('pedidos', () => {
  const orders = ref([])
  const loading = ref(false)

  async function fetchOrders() {
    loading.value = true
    try {
      const response = await api.get('/orders')
      orders.value = response.data
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
      await fetchOrders()
    } catch (error) {
      console.error('Error al actualizar el estado:', error)
      toast.error('Hubo un error al procesar la solicitud.')
    }
  }

  async function marcarPagado(id) {
    try {
      await api.put(`/orders/${id}/marcar-pagado`)
      toast.success(`Pedido #${id.toString().padStart(4, '0')} marcado como pagado`)
      await fetchOrders()
    } catch (error) {
      console.error('Error al marcar como pagado:', error)
      toast.error('Hubo un error al procesar la solicitud.')
    }
  }

  /**
   * Crea un pedido manualmente desde el panel (ventas por teléfono/WhatsApp, crédito, etc.)
   */
  async function crearPedido(payload) {
    try {
      await api.post('/orders', payload)
      toast.success('Pedido registrado con éxito')
      await fetchOrders()
      return true
    } catch (error) {
      console.error('Error al crear el pedido:', error)
      if (error.response?.data?.errors) {
        const mensajes = Object.values(error.response.data.errors).flat().join(' | ')
        toast.error('Revisa los datos: ' + mensajes)
      } else {
        toast.error('No se pudo registrar el pedido')
      }
      return false
    }
  }

  return { orders, loading, fetchOrders, updateStatus, marcarPagado, crearPedido }
})
