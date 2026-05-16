/**
 * useScrollReveal
 * ───────────────
 * Composable para revelar elementos al entrar en el viewport.
 * Usa IntersectionObserver nativo — sin dependencias externas.
 *
 * IMPORTANTE: el estado inicial (opacity:0, transform) lo maneja
 * global.css con el selector [data-reveal]. Este composable solo
 * se encarga de hacer los elementos visibles cuando entran al viewport.
 * Esto elimina el parpadeo que ocurría cuando JS aplicaba opacity:0
 * después del primer render del navegador.
 */

import { onUnmounted } from 'vue'

// ── Detecta preferencia de movimiento reducido ────────────────────────────────
const prefersReducedMotion =
  typeof window !== 'undefined' &&
  window.matchMedia('(prefers-reduced-motion: reduce)').matches

/**
 * @param {Object}  options
 * @param {number}  options.threshold   - % del elemento visible para activar (0–1)
 * @param {string}  options.rootMargin  - margen del viewport
 * @param {boolean} options.once        - si solo se anima una vez
 * @param {number}  options.stagger     - delay entre elementos en revealAll (ms)
 */
export function useScrollReveal(options = {}) {
  const {
    threshold  = 0.15,
    rootMargin = '0px 0px -60px 0px',
    once       = true,
    stagger    = 100,
  } = options

  // Un solo observer compartido — más eficiente que uno por elemento
  let observer = null

  // Evita doble observación del mismo elemento
  const observed = new WeakSet()

  // ── Crea el observer compartido ─────────────────────────────────────────────
  function getObserver() {
    if (observer) return observer

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return

          // Hace el elemento visible — el CSS ya lo tenía en opacity:0
          entry.target.style.opacity   = '1'
          entry.target.style.transform = 'none'

          if (once) observer.unobserve(entry.target)
        })
      },
      { threshold, rootMargin },
    )

    return observer
  }

  /**
   * Observa un único elemento.
   * NO aplica estilos iniciales — los maneja global.css con [data-reveal].
   * Solo aplica el transition-delay si hay stagger.
   *
   * @param {HTMLElement} el    - elemento a revelar
   * @param {number}      delay - delay en ms para el stagger
   */
  function reveal(el, delay = 0) {
    // Si el usuario prefiere movimiento reducido, no hay nada que hacer
    // (el CSS ya mostró el elemento directamente)
    if (!el || observed.has(el) || prefersReducedMotion) return

    // Aplica el delay de stagger via transitionDelay
    if (delay > 0) {
      el.style.transitionDelay = `${delay}ms`
    }

    getObserver().observe(el)
    observed.add(el)
  }

  /**
   * Observa todos los [data-reveal] dentro de un contenedor.
   * Aplica stagger automático según el índice.
   *
   * @param {HTMLElement} container - elemento padre
   * @param {string}      selector  - selector CSS de los hijos a revelar
   */
  function revealAll(container, selector = '[data-reveal]') {
    if (!container) return

    const elements = container.querySelectorAll(selector)
    elements.forEach((el, i) => reveal(el, i * stagger))
  }

  // ── Limpieza al desmontar ────────────────────────────────────────────────────
  onUnmounted(() => {
    observer?.disconnect()
    observer = null
  })

  return { reveal, revealAll }
}