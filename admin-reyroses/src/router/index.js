import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import AdminLayout from '../layouts/AdminLayout.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
      meta: { requiresGuest: true },
    },
    {
      path: '/dashboard',
      component: AdminLayout,
      meta: { requiresAuth: true },
      redirect: { name: 'catalogo' },
      children: [
        {
          path: 'catalogo',
          name: 'catalogo',
          component: () => import('../views/CatalogoView.vue'),
        },
        {
          path: 'pedidos',
          name: 'pedidos',
          component: () => import('../views/PedidosView.vue'),
        },
        // {
        //   path: 'compras',
        //   name: 'compras',
        //   component: () => import('../views/ComprasView.vue'),
        // },
        // {
        //   path: 'caja',
        //   name: 'caja',
        //   component: () => import('../views/CajaView.vue'),
        // },
        // {
        //   path: 'inversion',
        //   name: 'inversion',
        //   component: () => import('../views/InversionView.vue'),
        // },
      ],
    },
  ],
})

// === GUARDIA DE SEGURIDAD (NAVIGATION GUARD) ===
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem('auth_token')

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' })
  } else if (to.meta.requiresGuest && isAuthenticated) {
    next({ name: 'catalogo' })
  } else {
    next()
  }
})

export default router
