import { ref, watch } from 'vue'

// Estado a nivel de módulo: todos los componentes que usen useTheme()
// comparten el mismo valor, no uno nuevo por componente.
const isDarkTheme = ref(localStorage.getItem('theme') !== 'light')

watch(isDarkTheme, (value) => {
  localStorage.setItem('theme', value ? 'dark' : 'light')
})

export function useTheme() {
  const toggleTheme = () => {
    isDarkTheme.value = !isDarkTheme.value
  }

  return { isDarkTheme, toggleTheme }
}
