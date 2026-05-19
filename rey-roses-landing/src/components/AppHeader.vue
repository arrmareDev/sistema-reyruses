<template>
  <header class="header" :class="{
    'header--scrolled': isScrolled,
    'header--menu-open': menuOpen,
  }" role="banner">
    <!-- ── Barra principal ── -->
    <div class="header__bar">
      <div class="container header__inner">

        <!-- Logo como imagen -->
        <a href="#inicio" class="header__logo" aria-label="REY ROSES — Ir al inicio">
          <img src="/images/logo.png" alt="REY ROSES — Productora y Distribuidora de Rosas Ecuatorianas"
            class="header__logo-img"  loading="eager" decoding="async" fetchpriority="high" />
        </a>

        <!-- Navegación desktop -->
        <nav class="header__nav" aria-label="Navegación principal">
          <ul role="list" class="header__nav-list">
            <li v-for="link in NAV_LINKS" :key="link.href">
              <a :href="link.href" class="header__nav-link"
                :aria-current="activeSection === link.id ? 'page' : undefined" @click="closeMenu">
                {{ link.label }}
              </a>
            </li>
          </ul>
        </nav>

        <!-- CTA desktop -->
        <a href="#contacto" class="header__cta" aria-label="Solicitar catálogo de productos REY ROSES"
          @click="closeMenu">
          Solicitar Catálogo
        </a>

        <!-- Burger mobile -->
        <button class="header__burger" type="button" :aria-expanded="menuOpen" aria-controls="mobile-menu"
          :aria-label="menuOpen ? 'Cerrar menú' : 'Abrir menú de navegación'" @click="toggleMenu">
          <span class="burger__line"></span>
          <span class="burger__line"></span>
          <span class="burger__line"></span>
        </button>

      </div>
    </div>

    <!-- ── Menú mobile ── -->
    <Transition name="menu-slide">
      <div v-if="menuOpen" id="mobile-menu" class="header__mobile" role="dialog" aria-modal="true"
        aria-label="Menú de navegación">
        <!-- Logo dentro del menú mobile -->
        <div class="mobile__header">
          <img src="/images/logo.png" alt="REY ROSES" class="mobile__logo"  loading="lazy"
            decoding="async" aria-hidden="true" />
        </div>

        <nav aria-label="Menú móvil">
          <ul role="list" class="mobile__list">
            <li v-for="(link, index) in NAV_LINKS" :key="link.href" class="mobile__item">
              <a :href="link.href" class="mobile__link" :aria-current="activeSection === link.id ? 'page' : undefined"
                @click="closeMenu">
                <span class="mobile__link-num" aria-hidden="true">
                  {{ String(index + 1).padStart(2, '0') }}
                </span>
                {{ link.label }}
              </a>
            </li>
          </ul>
        </nav>

        <!-- Footer del menú -->
        <div class="mobile__footer">
          <a href="#contacto" class="mobile__cta" @click="closeMenu">
            Solicitar Catálogo
          </a>
          <p class="mobile__tagline">
            Productora y Distribuidora de Rosas Ecuatorianas
          </p>
        </div>

      </div>
    </Transition>

  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

// ── Constantes ───────────────────────────────────────────────────────────────

/** Links de navegación — agrega o quita secciones solo aquí */
const NAV_LINKS = [
  { href: '#inicio', label: 'Inicio', id: 'inicio' },
  { href: '#nosotros', label: 'Nosotros', id: 'nosotros' },
  { href: '#productos', label: 'Productos', id: 'productos' },
  { href: '#contacto', label: 'Contacto', id: 'contacto' },
]

const SCROLL_THRESHOLD = 40 // px para activar estado scrolled

// ── Estado reactivo ──────────────────────────────────────────────────────────
const isScrolled = ref(false)
const menuOpen = ref(false)
const activeSection = ref('inicio')

// ── Funciones ────────────────────────────────────────────────────────────────

/** Sombra progresiva al hacer scroll */
function onScroll() {
  isScrolled.value = window.scrollY > SCROLL_THRESHOLD
  detectActiveSection()
}

/** Marca el link activo según la sección visible */
function detectActiveSection() {
  const offset = 100 // px de margen superior
  const scrollY = window.scrollY + offset

  for (let i = NAV_LINKS.length - 1; i >= 0; i--) {
    const el = document.getElementById(NAV_LINKS[i].id)
    if (el && scrollY >= el.offsetTop) {
      activeSection.value = NAV_LINKS[i].id
      break
    }
  }
}

function toggleMenu() {
  menuOpen.value = !menuOpen.value
  // Bloquea scroll del body mientras el menú está abierto
  document.body.style.overflow = menuOpen.value ? 'hidden' : ''
}

function closeMenu() {
  menuOpen.value = false
  document.body.style.overflow = ''
}

/** Cierra el menú con tecla Escape — accesibilidad */
function onKeydown(e) {
  if (e.key === 'Escape' && menuOpen.value) closeMenu()
}

// ── Lifecycle ────────────────────────────────────────────────────────────────
onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  window.addEventListener('keydown', onKeydown)
})

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll)
  window.removeEventListener('keydown', onKeydown)
  document.body.style.overflow = ''
})
</script>

<style scoped>
/* ═══════════════════════════════════════════════════════
   HEADER — contenedor raíz
   ═══════════════════════════════════════════════════════ */
.header {
  /* Variables locales — fácil de sobreescribir desde el padre */
  --hdr-h: var(--header-h, 72px);
  --hdr-border-color: rgba(212, 131, 10, 0.15);
  --hdr-shadow: 0 1px 0 var(--hdr-border-color), 0 4px 20px rgba(0, 0, 0, 0.04);
  --hdr-shadow-deep: 0 1px 0 var(--hdr-border-color), 0 8px 32px rgba(212, 131, 10, 0.1);

  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 200;
  height: var(--hdr-h);
}

/* ── Barra blanca ── */
.header__bar {
  height: var(--hdr-h);
  background: var(--wh);
  border-bottom: 1px solid var(--hdr-border-color);
  box-shadow: var(--hdr-shadow);
  transition:
    box-shadow var(--transition-base),
    background var(--transition-base);
}

/* Al hacer scroll: fondo translúcido con blur */
.header--scrolled .header__bar {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  box-shadow: var(--hdr-shadow-deep);
}

/* ── Flex inner ── */
.header__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 100%;
  gap: var(--space-6);
}

/* ═══════════════════════════════════════════════════════
   LOGO — imagen desde public/images/logo.png
   ═══════════════════════════════════════════════════════ */
.header__logo {
  display: inline-flex;
  align-items: center;
  flex-shrink: 0;
  /* Sin color heredado — la imagen ya tiene sus propios colores */
  transition: opacity var(--transition-fast);
}

.header__logo:hover {
  opacity: 0.75;
}

.header__logo-img {
  height: 120px;
  width: auto;
  /* object-fit por si el contenedor tiene dimensiones fijas */
  object-fit: contain;
  object-position: left center;
  /* Sin degradaciones de color — el logo negro es correcto sobre blanco */
  display: block;
}

/* ═══════════════════════════════════════════════════════
   NAVEGACIÓN DESKTOP
   ═══════════════════════════════════════════════════════ */
.header__nav {
  display: none;
  /* visible desde 768px */
  flex: 1;
  justify-content: center;
}

.header__nav-list {
  display: flex;
  align-items: center;
  gap: clamp(1.5rem, 3vw, 2.5rem);
}

.header__nav-link {
  position: relative;
  display: inline-block;
  font-family: var(--font-body);
  font-size: 0.80rem;
  font-weight: 750;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--bk);
  opacity: 0.5;
  padding-bottom: 3px;
  transition: opacity var(--transition-fast);
}

/* Subrayado naranja animado */
.header__nav-link::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background: var(--or);
  border-radius: 1px;
  transition: width var(--transition-base);
}

.header__nav-link:hover,
.header__nav-link[aria-current='page'] {
  opacity: 1;
}

.header__nav-link:hover::after,
.header__nav-link[aria-current='page']::after {
  width: 100%;
}

/* ═══════════════════════════════════════════════════════
   CTA DESKTOP — outline que se rellena en hover
   ═══════════════════════════════════════════════════════ */
.header__cta {
  display: none;
  /* visible desde 768px */
  align-items: center;
  flex-shrink: 0;
  font-family: var(--font-body);
  font-size: 0.62rem;
  font-weight: 600;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--or);
  border: 1.5px solid var(--or);
  padding: 9px 20px;
  border-radius: var(--radius-sm);
  transition:
    background var(--transition-fast),
    color var(--transition-fast),
    transform var(--transition-fast),
    box-shadow var(--transition-fast);
}

.header__cta:hover {
  background: var(--or);
  color: var(--wh);
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(212, 131, 10, 0.28);
}

.header__cta:active {
  transform: translateY(0);
}

/* ═══════════════════════════════════════════════════════
   BURGER — Mobile
   ═══════════════════════════════════════════════════════ */
.header__burger {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  width: 40px;
  height: 40px;
  padding: 6px;
  border-radius: var(--radius-sm);
  cursor: pointer;
  transition: background var(--transition-fast);
}

.header__burger:hover {
  background: rgba(212, 131, 10, 0.06);
}

.burger__line {
  display: block;
  height: 1.5px;
  background: var(--bk);
  border-radius: 1px;
  transform-origin: center;
  transition:
    transform var(--transition-base),
    opacity var(--transition-base),
    width var(--transition-base);
}

/* Tercera línea más corta — detalle estético */
.burger__line:last-child {
  width: 60%;
  align-self: flex-end;
}

/* Animación a ✕ cuando el menú está abierto */
.header--menu-open .burger__line:nth-child(1) {
  transform: translateY(6.5px) rotate(45deg);
}

.header--menu-open .burger__line:nth-child(2) {
  opacity: 0;
  transform: scaleX(0);
}

.header--menu-open .burger__line:nth-child(3) {
  width: 100%;
  transform: translateY(-6.5px) rotate(-45deg);
}

/* ═══════════════════════════════════════════════════════
   MENÚ MOBILE
   ═══════════════════════════════════════════════════════ */
.header__mobile {
  position: fixed;
  inset: var(--hdr-h) 0 0 0;
  background: var(--wh);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: var(--space-8) var(--container-pad, 1.5rem) var(--space-12);
  overflow-y: auto;
  border-top: 1px solid var(--hdr-border-color);
  gap: var(--space-8);
}

/* Logo dentro del menú mobile */
.mobile__header {
  display: flex;
  justify-content: center;
  padding-bottom: var(--space-8);
  border-bottom: 1px solid rgba(0, 0, 0, 0.06);
}

.mobile__logo {
  height: 80px;
  /* un poco más grande dentro del menú */
  width: auto;
  object-fit: contain;
  /* Filtro para oscurecer si la imagen tiene partes muy claras */
  opacity: 0.85;
}

/* Lista de links mobile */
.mobile__list {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.mobile__item {
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.mobile__link {
  display: flex;
  align-items: center;
  gap: var(--space-4);
  padding: 1.1rem 0;
  font-family: var(--font-display);
  font-size: clamp(1.5rem, 5vw, 2rem);
  font-weight: 700;
  color: var(--bk);
  transition:
    color var(--transition-fast),
    padding-left var(--transition-base);
}

.mobile__link:hover,
.mobile__link[aria-current='page'] {
  color: var(--or);
  padding-left: var(--space-2);
}

/* Número de sección */
.mobile__link-num {
  font-family: var(--font-body);
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 0.14em;
  color: var(--or);
  opacity: 0.65;
  min-width: 20px;
  flex-shrink: 0;
}

/* Footer del menú mobile */
.mobile__footer {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: var(--space-4);
  margin-top: auto;
  padding-top: var(--space-8);
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.mobile__cta {
  display: inline-flex;
  font-family: var(--font-body);
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  background: var(--or);
  color: var(--wh);
  padding: 12px 28px;
  border-radius: var(--radius-sm);
  box-shadow: 0 4px 16px rgba(212, 131, 10, 0.25);
  transition:
    background var(--transition-fast),
    transform var(--transition-fast);
}

.mobile__cta:hover {
  background: var(--or2);
  transform: translateY(-1px);
}

.mobile__tagline {
  font-family: var(--font-body);
  font-size: 0.6rem;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--gr2);
}

/* ═══════════════════════════════════════════════════════
   TRANSICIÓN — Menú slide suave
   ═══════════════════════════════════════════════════════ */
.menu-slide-enter-active {
  transition:
    opacity 0.22s ease,
    transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

.menu-slide-leave-active {
  transition:
    opacity 0.18s ease,
    transform 0.22s ease;
}

.menu-slide-enter-from,
.menu-slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* ═══════════════════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════════════════ */
@media (min-width: 768px) {

  .header__nav,
  .header__cta {
    display: flex;
  }

  .header__burger {
    display: none;
  }

  /* El menú mobile nunca debe verse en desktop */
  .header__mobile {
    display: none !important;
  }
}

/* ═══════════════════════════════════════════════════════
   ACCESIBILIDAD — prefers-reduced-motion
   ═══════════════════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {

  .header__bar,
  .header__logo,
  .header__nav-link,
  .header__nav-link::after,
  .header__cta,
  .header__burger,
  .burger__line,
  .mobile__link,
  .mobile__cta,
  .menu-slide-enter-active,
  .menu-slide-leave-active {
    transition: none;
    animation: none;
  }
}
</style>