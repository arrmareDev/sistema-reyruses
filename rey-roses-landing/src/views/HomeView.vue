<template>
  <div class="home-view">
    <AppHeader />

    <main id="main-content" tabindex="-1">
      <HeroSection />
      <AboutSection />
      <ProductsSection />
      <ContactSection />
    </main>

    <AppFooter />

    <!-- Botón volver arriba -->
    <Transition name="fade-up">
      <button v-if="showScrollTop" class="scroll-top" type="button" aria-label="Volver al inicio" @click="scrollToTop">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false">
          <path d="M9 14V4M4 9l5-5 5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
      </button>
    </Transition>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import AppHeader from '@/components/AppHeader.vue'
import HeroSection from '@/components/HeroSection.vue'
import AboutSection from '@/components/AboutSection.vue'
import ProductsSection from '@/components/ProductsSection.vue'
import ContactSection from '@/components/ContactSection.vue'
import AppFooter from '@/components/AppFooter.vue'

const showScrollTop = ref(false)

function handleScroll() {
  showScrollTop.value = window.scrollY > 400
}

function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

onMounted(() => window.addEventListener('scroll', handleScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', handleScroll))
</script>

<style scoped>
/* ── Botón volver arriba ── */
.scroll-top {
  position: fixed;
  bottom: var(--space-8);
  right: var(--space-8);
  z-index: 50;
  width: 46px;
  height: 46px;

  /* Paleta naranja de la marca — antes usaba --color-rose-deep que no existe */
  background: var(--or);
  color: var(--wh);

  border-radius: var(--radius-full);
  border: none;
  cursor: pointer;

  display: flex;
  align-items: center;
  justify-content: center;

  /* Sombra con tinte naranja — más coherente con la marca */
  box-shadow: 0 4px 16px rgba(212, 131, 10, 0.4);

  transition:
    background var(--transition-fast),
    transform var(--transition-fast),
    box-shadow var(--transition-fast);
}

.scroll-top:hover {
  background: var(--or2);
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(212, 131, 10, 0.5);
}

.scroll-top:active {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(212, 131, 10, 0.3);
}

/* ── Transición entrada / salida ── */
.fade-up-enter-active,
.fade-up-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.fade-up-enter-from,
.fade-up-leave-to {
  opacity: 0;
  transform: translateY(12px);
}

/* ── Accesibilidad ── */
@media (prefers-reduced-motion: reduce) {

  .scroll-top,
  .fade-up-enter-active,
  .fade-up-leave-active {
    transition: none;
  }
}
</style>