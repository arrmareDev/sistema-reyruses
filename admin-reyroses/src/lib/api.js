import axios from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/api',
})

// Adjunta el token en cada request, sin tener que repetirlo en cada componente
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Si el token venció o es inválido, mandamos al login sin que cada componente
// tenga que manejarlo por su cuenta
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('auth_token')
      if (window.location.pathname !== '/') {
        window.location.href = '/'
      }
    }
    return Promise.reject(error)
  },
)

export default api
