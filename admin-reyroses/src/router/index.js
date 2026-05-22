import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue' // Importamos el dashboard

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      // Indicamos que esta ruta es para usuarios NO logueados
      meta: { requiresGuest: true }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView, // Creamos la nueva ruta
      // Indicamos que esta ruta requiere sesión iniciada
      meta: { requiresAuth: true }
    },
  ],
})

// === GUARDIA DE SEGURIDAD (NAVIGATION GUARD) ===
router.beforeEach((to, from, next) => {
  // Buscamos el token que guardaste al hacer login
  const isAuthenticated = localStorage.getItem('auth_token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Si la ruta requiere auth y no hay token, lo pateamos al login
    next({ name: 'login' })
  } else if (to.meta.requiresGuest && isAuthenticated) {
    // Si intenta ir al login pero ya está autenticado, lo mandamos al dashboard
    next({ name: 'dashboard' })
  } else {
    // Si todo está correcto, lo dejamos pasar
    next()
  }
})

export default router
