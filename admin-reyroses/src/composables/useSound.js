import { ref, watch } from 'vue'

const isSoundEnabled = ref(localStorage.getItem('soundEnabled') !== 'false')

watch(isSoundEnabled, (value) => {
  localStorage.setItem('soundEnabled', value)
})

export function useSound() {
  const toggleSound = () => {
    isSoundEnabled.value = !isSoundEnabled.value
  }

  const playAlert = () => {
    if (!isSoundEnabled.value) return
    const audio = new Audio('/alert.mp3')
    audio.play().catch(() => {
      // El navegador puede bloquear el autoplay hasta que haya interacción; no es crítico.
    })
  }

  return { isSoundEnabled, toggleSound, playAlert }
}
