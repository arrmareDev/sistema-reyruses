import { defineStore } from 'pinia'
import { ref } from 'vue'
import { toast } from 'vue-sonner'
import api from '@/lib/api'

export const TALLOS = [50, 60, 70, 80, 90]

export function emptyTabaco() {
  return {
    product_id: '',
    costo_tabaco_usd: '',
    desglose: { 50: 0, 60: 0, 70: 0, 80: 0, 90: 0 },
  }
}

export function emptyCompraForm() {
  return {
    fecha: new Date().toISOString().slice(0, 10),
    proveedor: '',
    tipo_cambio: '',
    comision_western: '',
    pago_trabajadores: 0,
    saldo_inversion_usado: 0,
    tabacos: [emptyTabaco()],
  }
}

export const useComprasStore = defineStore('compras', () => {
  const compras = ref([])
  const loading = ref(false)

  async function fetchCompras() {
    loading.value = true
    try {
      const response = await api.get('/compras')
      compras.value = response.data
    } catch (error) {
      console.error('Error cargando compras:', error)
      toast.error('No se pudieron cargar las compras')
    } finally {
      loading.value = false
    }
  }

  /**
   * Arma el payload que espera el backend (desglose como array, sin tallos en 0)
   * y crea la compra. Devuelve true si se guardó bien.
   */
  async function createCompra(form) {
    const payload = {
      fecha: form.fecha,
      proveedor: form.proveedor || null,
      tipo_cambio: form.tipo_cambio,
      comision_western: form.comision_western || null,
      pago_trabajadores: form.pago_trabajadores || 0,
      saldo_inversion_usado: form.saldo_inversion_usado || 0,
      tabacos: form.tabacos.map((tabaco) => ({
        product_id: tabaco.product_id,
        costo_tabaco_usd: tabaco.costo_tabaco_usd,
        desglose: TALLOS.filter((tallo) => Number(tabaco.desglose[tallo]) > 0).map((tallo) => ({
          tallo,
          cantidad_paquetes: Number(tabaco.desglose[tallo]),
        })),
      })),
    }

    try {
      await api.post('/compras', payload)
      toast.success('Compra registrada con éxito')
      await fetchCompras()
      return true
    } catch (error) {
      console.error('Error al registrar la compra:', error)
      if (error.response?.data?.errors) {
        const mensajes = Object.values(error.response.data.errors).flat().join(' | ')
        toast.error('Revisa los datos: ' + mensajes)
      } else if (error.response?.data?.message) {
        toast.error(error.response.data.message)
      } else {
        toast.error('Ocurrió un error inesperado al guardar la compra.')
      }
      return false
    }
  }

  /**
   * Solo campos financieros (ej: comisión del Western cuando la agencia la confirma).
   */
  async function updateCompra(id, data) {
    try {
      await api.put(`/compras/${id}`, data)
      toast.success('Compra actualizada correctamente')
      await fetchCompras()
      return true
    } catch (error) {
      console.error('Error al actualizar la compra:', error)
      toast.error('No se pudo actualizar la compra')
      return false
    }
  }

  async function recibirCompra(id) {
    try {
      await api.post(`/compras/${id}/recibir`)
      toast.success('Compra recibida: stock del catálogo actualizado')
      await fetchCompras()
    } catch (error) {
      console.error('Error al recibir la compra:', error)
      toast.error(error.response?.data?.message || 'No se pudo marcar la compra como recibida')
    }
  }

  async function deleteCompra(id) {
    try {
      await api.delete(`/compras/${id}`)
      toast.success('Compra eliminada')
      await fetchCompras()
    } catch (error) {
      console.error('Error al eliminar la compra:', error)
      toast.error(error.response?.data?.message || 'No se pudo eliminar la compra')
    }
  }

  return { compras, loading, fetchCompras, createCompra, updateCompra, recibirCompra, deleteCompra }
})
