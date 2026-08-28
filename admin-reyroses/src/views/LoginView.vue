<template>
  <div
    :class="['flex items-center justify-center min-h-screen font-sans relative overflow-hidden transition-colors duration-300', isDarkTheme ? 'bg-[#0f0f12]' : 'bg-gray-100']">
    <!-- Background decorative elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div
        :class="['absolute -top-40 -right-40 w-80 h-80 rounded-full blur-3xl', isDarkTheme ? 'bg-emerald-500/10' : 'bg-emerald-500/20']">
      </div>
      <div
        :class="['absolute -bottom-40 -left-40 w-80 h-80 rounded-full blur-3xl', isDarkTheme ? 'bg-emerald-500/5' : 'bg-emerald-500/10']">
      </div>
    </div>

    <!-- Theme toggle button -->
    <button @click="toggleTheme"
      :class="['absolute top-6 right-6 p-3 rounded-xl transition-all z-20', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-900 border border-gray-200 shadow-sm']"
      :title="isDarkTheme ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro'">
      <Sun v-if="isDarkTheme" class="w-5 h-5" :stroke-width="2" />
      <Moon v-else class="w-5 h-5" :stroke-width="2" />
    </button>

    <div
      :class="['w-full max-w-md p-10 rounded-2xl shadow-2xl relative z-10', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
      <!-- Header -->
      <div class="text-center mb-10 flex flex-col items-center">
        <!-- Logo container with glow effect -->
        <div class="relative mb-6">
          <div class="absolute inset-0 bg-emerald-500/20 rounded-2xl blur-xl"></div>
          <img src="/rey.webp" alt="Rey Roses Logo"
            class="w-28 h-auto rounded-2xl shadow-lg object-contain relative z-10 border border-[#2a2a32]" />
        </div>
        <h1 :class="['text-2xl font-bold tracking-tight mb-2', isDarkTheme ? 'text-white' : 'text-gray-900']">Rey Roses
        </h1>
        <p
          :class="['text-xs font-semibold tracking-widest uppercase', isDarkTheme ? 'text-[#8a8a8e]' : 'text-gray-500']">
          Panel de Administracion
        </p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <!-- Email field -->
        <div>
          <label
            :class="['block text-xs font-semibold uppercase tracking-wider mb-2.5', isDarkTheme ? 'text-[#8a8a8e]' : 'text-gray-500']">
            Correo Electronico
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <Mail :class="['w-5 h-5', isDarkTheme ? 'text-[#5a5a5e]' : 'text-gray-400']" :stroke-width="1.5" />
            </div>
            <input v-model="form.email" type="email" required placeholder="admin@reyroses.com"
              :class="['w-full pl-12 pr-4 py-3.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all duration-200', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white placeholder-[#5a5a5e]' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']" />
          </div>
        </div>

        <!-- Password field -->
        <div>
          <label
            :class="['block text-xs font-semibold uppercase tracking-wider mb-2.5', isDarkTheme ? 'text-[#8a8a8e]' : 'text-gray-500']">
            Contrasena
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
              <Lock :class="['w-5 h-5', isDarkTheme ? 'text-[#5a5a5e]' : 'text-gray-400']" :stroke-width="1.5" />
            </div>
            <input v-model="form.password" type="password" required placeholder="••••••••"
              :class="['w-full pl-12 pr-4 py-3.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all duration-200', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white placeholder-[#5a5a5e]' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']" />
          </div>
        </div>

        <!-- Error message -->
        <div v-if="errorMessage" class="flex items-center gap-3 p-4 bg-red-500/10 border border-red-500/20 rounded-xl">
          <CircleAlert class="w-5 h-5 text-red-400 flex-shrink-0" :stroke-width="2" />
          <span class="text-red-400 text-sm font-medium">{{ errorMessage }}</span>
        </div>

        <!-- Submit button -->
        <button type="submit"
          class="w-full py-4 mt-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold uppercase tracking-widest rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 flex items-center justify-center gap-2">
          <span>Ingresar</span>
          <ArrowRight class="w-5 h-5" :stroke-width="2" />
        </button>
      </form>

      <!-- Footer -->
      <div :class="['mt-8 pt-6 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
        <p :class="['text-center text-xs', isDarkTheme ? 'text-[#5a5a5e]' : 'text-gray-500']">
          Acceso exclusivo para administradores
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue-sonner'
import { Sun, Moon, Mail, Lock, CircleAlert, ArrowRight } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { useAuthStore } from '@/stores/auth'

const { isDarkTheme, toggleTheme } = useTheme()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')
const router = useRouter()

const handleLogin = async () => {
  errorMessage.value = ''
  try {
    const user = await authStore.login(form.value.email, form.value.password)
    toast.success('Bienvenido al panel, ' + user.name + '!')
    router.push({ name: 'catalogo' })
  } catch (error) {
    errorMessage.value = 'Credenciales incorrectas o error de conexion.'
    console.error(error)
  }
}
</script>
