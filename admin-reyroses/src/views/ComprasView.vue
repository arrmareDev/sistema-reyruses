<template>
  <div class="flex flex-col h-full">
    <header
      :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
      <div>
        <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Compras</h1>
        <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          {{ comprasStore.compras.length }} pedidos de compra registrados
        </p>
      </div>
      <button @click="showForm = true"
        class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
        <Plus class="w-4 h-4" :stroke-width="2" />
        Nueva compra
      </button>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
      <div v-if="comprasStore.compras.length === 0" class="text-center py-20">
        <div
          :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
          <Truck :class="['w-8 h-8', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']" :stroke-width="1.5" />
        </div>
        <p :class="['text-base', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Aún no hay compras registradas</p>
        <p :class="['text-sm mt-1', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">Registra tu primer pedido de
          tabacos</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <CompraCard v-for="compra in comprasStore.compras" :key="compra.id" :compra="compra"
          @click="openDetail(compra)" />
      </div>
    </div>

    <CompraForm v-model="showForm" />

    <CompraDetailModal v-model="showDetail" :compra="selectedCompra" @recibir="openRecibirConfirm" />

    <ConfirmModal v-model="showRecibirConfirm" title="Recibir compra"
      message="Esto aumentará el stock del catálogo según el desglose de tallos de cada tabaco. No se puede deshacer."
      confirm-label="Sí, recibir" @confirm="executeRecibir" @cancel="showRecibirConfirm = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Plus, Truck } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useComprasStore } from '@/stores/compras'
import CompraCard from '@/components/compras/CompraCard.vue'
import CompraForm from '@/components/compras/CompraForm.vue'
import CompraDetailModal from '@/components/compras/CompraDetailModal.vue'
import ConfirmModal from '@/components/shared/ConfirmModal.vue'

const { isDarkTheme } = useTheme()
const comprasStore = useComprasStore()

const showForm = ref(false)
const showDetail = ref(false)
const selectedCompra = ref(null)

const showRecibirConfirm = ref(false)
const pendingRecibirId = ref(null)

const openDetail = (compra) => {
  selectedCompra.value = compra
  showDetail.value = true
}

const openRecibirConfirm = (id) => {
  pendingRecibirId.value = id
  showRecibirConfirm.value = true
}

const executeRecibir = async () => {
  await comprasStore.recibirCompra(pendingRecibirId.value)
  showRecibirConfirm.value = false
  showDetail.value = false
}

onMounted(() => {
  comprasStore.fetchCompras()
})
</script>
