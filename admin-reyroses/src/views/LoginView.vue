<template>
  <div class="flex items-center justify-center min-h-screen bg-neutral-100 font-sans">
    <div class="w-full max-w-md p-10 bg-white rounded-2xl shadow-xl border border-neutral-100">
      <div class="text-center mb-8 flex flex-col items-center">
        <!-- Logo de Rey Ruses -->
        <img
          src="/rey.webp"
          alt="Rey Ruses Logo"
          class="w-32 h-auto mb-4 rounded-lg shadow-sm object-contain"
        />
        <p class="text-xs text-neutral-400 font-semibold tracking-widest uppercase">
          Panel de Administración
        </p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-6">
        <div>
          <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
            >Correo Electrónico</label
          >
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-lg text-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#E8751A] focus:border-transparent transition-all"
          />
        </div>

        <div>
          <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
            >Contraseña</label
          >
          <input
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-lg text-neutral-800 focus:outline-none focus:ring-2 focus:ring-[#E8751A] focus:border-transparent transition-all"
          />
        </div>

        <div v-if="errorMessage" class="text-red-500 text-sm text-center font-medium">
          {{ errorMessage }}
        </div>

        <button
          type="submit"
          class="w-full py-3.5 mt-4 bg-[#E8751A] text-white font-bold uppercase tracking-widest rounded-lg hover:bg-[#d66a15] transition-colors shadow-md"
        >
          Ingresar
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router' //
import { toast } from 'vue-sonner' // <-- NUEVO: Importamos la alerta

const form = ref({
  email: '',
  password: '',
})

const errorMessage = ref('')
const router = useRouter() //

const handleLogin = async () => {
  errorMessage.value = ''
  try {
    const response = await axios.post(
      import.meta.env.VITE_API_URL + '/api/login',
      {
        email: form.value.email,
        password: form.value.password,
      },
      {
        headers: { Accept: 'application/json' },
      },
    )

    console.log('¡Éxito!', response.data)
    toast.success('¡Bienvenido al panel, ' + response.data.user.name + '!')

    localStorage.setItem('auth_token', response.data.token)

    router.push('/dashboard')
  } catch (error) {
    errorMessage.value = 'Credenciales incorrectas o error de conexión.'
    console.error(error)
  }
}
</script>
