import { toast } from 'vue-sonner'
import { requestForToken, onMessageListener } from '@/firebase'
import api from '@/lib/api'

export function useNotifications() {
  const enableNotifications = async () => {
    try {
      const token = await requestForToken()
      if (token) {
        await api.post('/save-fcm-token', { fcm_token: token })
        toast.success('¡Notificaciones activadas correctamente!')
      } else {
        toast.error('No diste permiso o hubo un error al generar el token.')
      }
    } catch (error) {
      console.error('Error activando notificaciones:', error)
      toast.error('Ocurrió un problema al activar las notificaciones.')
    }
  }

  const listenForNewOrders = (onNewOrder) => {
    try {
      onMessageListener((payload) => onNewOrder(payload))
    } catch (error) {
      console.error('Error al configurar el escuchador de Firebase:', error)
    }
  }

  return { enableNotifications, listenForNewOrders }
}
