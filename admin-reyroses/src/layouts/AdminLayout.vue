<template>
  <div
    :class="['flex h-screen font-sans overflow-hidden relative transition-colors duration-300', isDarkTheme ? 'bg-[#0f0f12]' : 'bg-gray-100']">
    <div v-if="isMobileMenuOpen" @click="isMobileMenuOpen = false"
      :class="['fixed inset-0 z-40 md:hidden backdrop-blur-sm transition-opacity', isDarkTheme ? 'bg-black/70' : 'bg-black/50']">
    </div>

    <aside :class="[
      'w-72 text-white flex flex-col shadow-2xl z-50 absolute inset-y-0 left-0 transform transition-all duration-300 ease-in-out md:relative md:translate-x-0',
      isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
      isDarkTheme ? 'bg-[#16161a] border-r border-[#2a2a32]' : 'bg-white border-r border-gray-200'
    ]">
      <div :class="['p-6 relative', isDarkTheme ? 'border-b border-[#2a2a32]' : 'border-b border-gray-200']">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
            <Flower2 class="w-6 h-6 text-white" :stroke-width="2" />
          </div>
          <div>
            <h2 :class="['text-lg font-bold tracking-wide', isDarkTheme ? 'text-white' : 'text-gray-900']">Rey Ruses
            </h2>
          </div>
        </div>
        <button @click="isMobileMenuOpen = false"
          :class="['md:hidden absolute top-4 right-4 p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-500 hover:text-gray-900']">
          <X class="w-5 h-5" :stroke-width="2" />
        </button>
      </div>

      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <p
          :class="['text-[10px] font-semibold uppercase tracking-widest mb-2 px-3', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">
          Ventas
        </p>
        <SidebarLink to="/dashboard/catalogo" label="Catálogo" :icon="Boxes" />
        <SidebarLink to="/dashboard/inventario" label="Inventario" :icon="Archive" />
        <SidebarLink to="/dashboard/pedidos" label="Pedidos" :icon="ClipboardList" />

        <p
          :class="['text-[10px] font-semibold uppercase tracking-widest mt-5 mb-2 px-3', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">
          Abastecimiento
        </p>
        <SidebarLink to="/dashboard/compras" label="Compras" :icon="Truck" />
        <SidebarLink to="/dashboard/inversion" label="Inversión" :icon="PiggyBank" />

        <p
          :class="['text-[10px] font-semibold uppercase tracking-widest mt-5 mb-2 px-3', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">
          Finanzas
        </p>
        <SidebarLink to="/dashboard/caja" label="Caja" :icon="Wallet" />
        <SidebarLink to="/dashboard/cuentas-por-cobrar" label="Cuentas por Cobrar" :icon="HandCoins" />
      </nav>

      <div :class="['p-4', isDarkTheme ? 'border-t border-[#2a2a32]' : 'border-t border-gray-200']">
        <div :class="['flex items-center gap-3 p-3 rounded-xl mb-3', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
          <div
            class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-sm font-bold text-white">
            AD
          </div>
          <div class="flex-1 min-w-0">
            <p :class="['text-sm font-semibold truncate', isDarkTheme ? 'text-white' : 'text-gray-900']">
              {{ authStore.user?.name || 'Administrador' }}
            </p>
            <p :class="['text-xs truncate', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
              {{ authStore.user?.email || 'admin@reyroses.com' }}
            </p>
          </div>
        </div>

        <button @click="notifications.enableNotifications"
          class="w-full py-2.5 mb-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all flex items-center justify-center gap-2 shadow-md shadow-blue-500/20">
          <Bell class="w-4 h-4" :stroke-width="2" />
          Activar Alertas
        </button>

        <div class="flex gap-2 mb-2">
          <button @click="toggleTheme"
            :class="['flex-1 py-2 rounded-lg transition-all flex items-center justify-center', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:text-gray-900']"
            :title="isDarkTheme ? 'Tema claro' : 'Tema oscuro'">
            <Sun v-if="isDarkTheme" class="w-4 h-4" :stroke-width="2" />
            <Moon v-else class="w-4 h-4" :stroke-width="2" />
          </button>
          <button @click="toggleSound"
            :class="['flex-1 py-2 rounded-lg transition-all flex items-center justify-center', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:text-white' : 'bg-gray-100 text-gray-600 hover:text-gray-900']"
            :title="isSoundEnabled ? 'Silenciar alertas' : 'Activar alertas'">
            <Volume2 v-if="isSoundEnabled" class="w-4 h-4" :stroke-width="2" />
            <VolumeX v-else class="w-4 h-4" :stroke-width="2" />
          </button>
        </div>

        <button @click="handleLogout"
          class="w-full py-2.5 text-xs font-bold text-white bg-red-600/90 hover:bg-red-700 rounded-lg transition-all flex items-center justify-center gap-2">
          <LogOut class="w-4 h-4" :stroke-width="2" />
          Cerrar sesión
        </button>
      </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
      <header
        :class="['md:hidden flex items-center justify-between p-4', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
        <button @click="isMobileMenuOpen = true" :class="isDarkTheme ? 'text-white' : 'text-gray-900'">
          <Menu class="w-6 h-6" :stroke-width="2" />
        </button>
        <span :class="['font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Rey Ruses</span>
        <div class="w-6"></div>
      </header>

      <main class="flex-1 overflow-y-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue-sonner'
import {
  Flower2, X, Menu, Bell, Sun, Moon, Volume2, VolumeX, LogOut,
  Boxes, ClipboardList, Truck, PiggyBank, Wallet, HandCoins, Archive,
} from 'lucide-vue-next'
import { useAuthStore } from '@/stores/auth'
import { usePedidosStore } from '@/stores/pedidos'
import { useTheme } from '@/composables/useTheme'
import { useSound } from '@/composables/useSound'
import { useNotifications } from '@/composables/useNotifications'
import SidebarLink from '@/components/shared/SidebarLink.vue'

const { isDarkTheme, toggleTheme } = useTheme()
const { isSoundEnabled, toggleSound, playAlert } = useSound()
const notifications = useNotifications()
const authStore = useAuthStore()
const pedidosStore = usePedidosStore()
const router = useRouter()

const isMobileMenuOpen = ref(false)

const handleLogout = async () => {
  await authStore.logout()
  router.push({ name: 'login' })
}

// Escucha pedidos nuevos en tiempo real (Firebase) sin importar en qué módulo estés
onMounted(() => {
  notifications.listenForNewOrders((payload) => {
    playAlert()

    toast.success(
      `Nuevo pedido: ${payload.notification?.title || 'Ingreso'} - ${payload.notification?.body || ''}`,
      { duration: 10000 },
    )

    // Refresca la lista en segundo plano; si ya estás en Pedidos, la ves actualizada al toque
    pedidosStore.fetchOrders()
  })
})
</script>
