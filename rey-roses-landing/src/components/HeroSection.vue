<template>
  <section id="inicio" class="hero" aria-labelledby="hero-heading">

    <!-- Columna izquierda: contenido principal -->
    <div class="hero__left">

      <!-- Badge de origen -->
      <div class="hero__tag animate-fade-in" style="animation-delay: .1s">
        <span class="tag-dot" aria-hidden="true"></span>
        <span>Rosas de los Andes · Ecuador</span>
      </div>

      <!-- h1 único en la página -->
      <h1 id="hero-heading" class="hero__title animate-fade-in-up" style="animation-delay: .2s">
        LA FLOR QUE
      </h1>
      <p class="hero__title-it animate-fade-in-up" style="animation-delay: .3s" aria-hidden="true">
        define reyes
      </p>

      <p class="hero__sub animate-fade-in-up" style="animation-delay: .42s">
        Cultivadas a 2.800 metros de altitud en los Andes ecuatorianos.
        Luz solar intensa, tierra volcánica y el secreto de la naturaleza
        hacen nuestras rosas incomparables.
      </p>

      <!-- CTAs -->
      <nav class="hero__btns animate-fade-in-up" style="animation-delay: .54s" aria-label="Acciones principales">
        <a href="#productos" class="btn-or">
          Ver Colección
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </a>
        <a href="#nosotros" class="btn-outline-bk">Nuestra Historia</a>
      </nav>

      <!-- Estadísticas — dl semántico -->
      <dl class="hero__stats animate-fade-in-up" style="animation-delay: .66s" aria-label="Datos de la empresa">
        <div v-for="stat in stats" :key="stat.label" class="hero__stat">
          <dt class="stat-l">{{ stat.label }}</dt>
          <dd class="stat-n">{{ stat.value }}</dd>
        </div>
      </dl>
    </div>

    <!-- Columna derecha: imagen local -->
    <div class="hero__right">
      <img src="/images/hero.png"
        alt="Rosas ecuatorianas de tallo largo cultivadas en los Andes a 2.800 metros de altitud" class="hero__img"
        loading="eager" fetchpriority="high" decoding="async" width="900" height="1100" />
      <div class="hero__shade" aria-hidden="true"></div>

      <aside class="hero__badge" aria-label="REY ROSES: Exportador número uno de Ecuador">
        <strong class="badge-n">N°1</strong>
        <span class="badge-l">Exportador Ecuador</span>
      </aside>
    </div>

  </section>

  <!-- Marquee de confianza -->
  <div class="marquee-bar" aria-hidden="true" role="presentation">
    <div class="marquee-inner">
      <span v-for="trackIndex in 2" :key="trackIndex" class="marquee-track">
        <span v-for="item in marqueeItems" :key="item.text" class="marquee-item"
          :class="{ 'marquee-item--or': item.accent }">
          {{ item.text }}
          <span class="mq-dot"></span>
        </span>
      </span>
    </div>
  </div>
</template>

<script setup>

const stats = [
  { value: '15+', label: 'Años de experiencia' },
  { value: '200+', label: 'Variedades de rosa' },
  { value: '40+', label: 'Países exportados' },
]

const marqueeItems = [
  { text: 'REY ROSES', accent: true },
  { text: 'Rosas Ecuatorianas Premium', accent: false },
  { text: 'Fair Trade Certified', accent: false },
  { text: '200+ Variedades', accent: true },
  { text: 'GlobalG.A.P.', accent: false },
  { text: 'Cayambe, Ecuador', accent: false },
  { text: '40+ Países', accent: true },
  { text: 'Tallo 50–90 cm', accent: false },
  { text: 'Frescura Garantizada', accent: false },
]
</script>

<style scoped>
/* ─── Hero: layout principal ─── */
.hero {
  background: var(--wh3);
  display: grid;
  grid-template-columns: 1fr;
  /* Mobile-first: una columna */
  min-height: 100svh;
  /* svh respeta la barra del navegador móvil */
  padding-top: 0px;
  overflow: hidden;
}

/* ─── Columna izquierda ─── */
.hero__left {
  padding: clamp(2.5rem, 6vw, 5rem) var(--container-pad);
  display: flex;
  flex-direction: column;
  justify-content: center;
  position: relative;
  z-index: 2;
}

/* ─── Silueta de rosa decorativa de fondo ─── */
.hero__left::before {
  content: '';
  position: absolute;
  inset: 0;
  z-index: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 500' fill='none'%3E%3C!-- Capullo --%3E%3Cellipse cx='200' cy='80' rx='55' ry='70' stroke='%23D4830A' stroke-width='2' opacity='0.18'/%3E%3Cellipse cx='200' cy='90' rx='35' ry='50' stroke='%23D4830A' stroke-width='1.5' opacity='0.14'/%3E%3C!-- Pétalos exteriores --%3E%3Cpath d='M145 120 Q100 80 120 40 Q160 60 155 110' stroke='%23D4830A' stroke-width='1.8' opacity='0.16' fill='none'/%3E%3Cpath d='M255 120 Q300 80 280 40 Q240 60 245 110' stroke='%23D4830A' stroke-width='1.8' opacity='0.16' fill='none'/%3E%3Cpath d='M170 140 Q130 110 110 70 Q155 75 175 125' stroke='%23D4830A' stroke-width='1.5' opacity='0.13' fill='none'/%3E%3Cpath d='M230 140 Q270 110 290 70 Q245 75 225 125' stroke='%23D4830A' stroke-width='1.5' opacity='0.13' fill='none'/%3E%3C!-- Tallo --%3E%3Cline x1='200' y1='150' x2='200' y2='480' stroke='%23D4830A' stroke-width='2.5' opacity='0.15'/%3E%3C!-- Hojas --%3E%3Cpath d='M200 250 Q150 220 140 270 Q170 280 200 260' stroke='%23D4830A' stroke-width='1.8' opacity='0.16' fill='none'/%3E%3Cpath d='M200 320 Q250 290 260 340 Q230 350 200 330' stroke='%23D4830A' stroke-width='1.8' opacity='0.16' fill='none'/%3E%3Cpath d='M200 390 Q145 360 135 410 Q168 420 200 400' stroke='%23D4830A' stroke-width='1.5' opacity='0.13' fill='none'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 5% center;
  background-size: clamp(280px, 55%, 420px);
  pointer-events: none;
}

/* ─── Badge de origen ─── */
.hero__tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--orb);
  border: 1.5px solid rgba(212, 131, 10, 0.25);
  color: var(--or);
  font-size: 0.6rem;
  font-weight: 600;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  padding: 6px 14px;
  border-radius: var(--radius-sm);
  margin-bottom: 1.5rem;
  width: fit-content;
}

.tag-dot {
  width: 6px;
  height: 6px;
  background: var(--or);
  border-radius: 50%;
  flex-shrink: 0;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {

  0%,
  100% {
    opacity: 1;
    transform: scale(1);
  }

  50% {
    opacity: 0.5;
    transform: scale(0.8);
  }
}

/* ─── Tipografía ─── */
.hero__title {
  font-family: var(--font-display);
  font-size: clamp(2.6rem, 6vw, 5rem);
  font-weight: 900;
  line-height: 1;
  color: var(--bk);
  letter-spacing: 0.02em;
}

.hero__title-it {
  font-family: var(--font-display);
  font-size: clamp(2rem, 4.5vw, 3.8rem);
  font-weight: 400;
  font-style: italic;
  color: var(--or);
  line-height: 1.1;
  margin-bottom: 1.8rem;
}

.hero__sub {
  font-size: 0.85rem;
  color: var(--gr);
  line-height: 1.9;
  margin-bottom: 2.2rem;
  max-width: 440px;
}

/* ─── Botones ─── */
.hero__btns {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 2.5rem;
}

.btn-or {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--or);
  color: var(--wh);
  font-size: 0.65rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 12px 24px;
  border-radius: var(--radius-sm);
  box-shadow: var(--shadow-or);
  transition:
    background var(--transition-fast),
    transform var(--transition-fast),
    box-shadow var(--transition-fast);
}

.btn-or:hover {
  background: var(--or2);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(212, 131, 10, 0.4);
}

.btn-or:active {
  transform: translateY(0);
}

.btn-outline-bk {
  display: inline-flex;
  align-items: center;
  color: var(--bk);
  font-size: 0.65rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  border: 1.5px solid var(--gr3);
  padding: 11px 22px;
  border-radius: var(--radius-sm);
  transition:
    border-color var(--transition-fast),
    color var(--transition-fast);
}

.btn-outline-bk:hover {
  border-color: var(--or);
  color: var(--or);
}

/* ─── Estadísticas dl semántico ─── */
.hero__stats {
  display: flex;
  gap: 2.5rem;
  padding-top: 1.8rem;
  border-top: 1.5px solid var(--gr3);
}

.hero__stat {
  display: flex;
  flex-direction: column-reverse;
  /* número arriba, label abajo */
  gap: 2px;
}

.stat-n {
  /* <dd> — valor */
  font-family: var(--font-display);
  font-size: 2.2rem;
  font-weight: 900;
  color: var(--or);
  line-height: 1;
}

.stat-l {
  /* <dt> — etiqueta */
  font-size: 0.55rem;
  font-weight: 500;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: var(--gr2);
}

/* ─── Columna derecha: imagen ─── */
.hero__right {
  position: absolute;
  inset: 0;
  left: auto;
  width: 50%;
  overflow: hidden;
}

.hero__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center top;
  /* No corta la parte superior de la flor */
}

.hero__shade {
  position: absolute;
  inset: 0;
  background: linear-gradient(to right,
      var(--wh3) 0%,
      rgba(245, 243, 239, 0.1) 40%,
      transparent 100%);
  pointer-events: none;
}

/* ─── Badge flotante ─── */
.hero__badge {
  position: absolute;
  bottom: 2.5rem;
  left: 1.5rem;
  background: var(--or);
  color: var(--wh);
  padding: 0.9rem 1.2rem;
  border-radius: var(--radius-sm);
  box-shadow: 0 6px 20px rgba(212, 131, 10, 0.35);
  text-align: center;
}

.badge-n {
  display: block;
  font-family: var(--font-display);
  font-size: 1.8rem;
  font-weight: 900;
  line-height: 1;
}

.badge-l {
  display: block;
  font-size: 0.52rem;
  font-weight: 600;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  margin-top: 2px;
  opacity: 0.9;
}

/* ─── Marquee ─── */
.marquee-bar {
  border-top: 1.5px solid var(--gr3);
  border-bottom: 1.5px solid var(--gr3);
  background: var(--wh);
  padding: 0.65rem 0;
  overflow: hidden;
  white-space: nowrap;
}

/* Solo anima si el usuario no ha pedido reducir movimiento */
@media (prefers-reduced-motion: no-preference) {
  .marquee-inner {
    animation: marquee 22s linear infinite;
  }
}

.marquee-inner {
  display: inline-flex;
}

.marquee-track {
  display: inline-flex;
}

.marquee-item {
  font-size: 0.58rem;
  font-weight: 600;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  color: var(--bk);
  opacity: 0.4;
  padding: 0 2.2rem;
  display: inline-flex;
  align-items: center;
  gap: 1rem;
  user-select: none;
}

.marquee-item--or {
  color: var(--or);
  opacity: 1;
  font-weight: 700;
}

.mq-dot {
  width: 4px;
  height: 4px;
  background: var(--or);
  border-radius: 50%;
  opacity: 0.4;
  flex-shrink: 0;
}

/* ─── Responsive ─── */
@media (min-width: 900px) {
  .hero {
    grid-template-columns: 1fr 1fr;
  }

  .hero__right {
    position: relative;
    width: auto;
    inset: auto;
  }

  .hero__left {
    max-width: none;
  }
}

@media (max-width: 899px) {
  .hero__right {
    width: 100%;
    opacity: 0.12;
  }

  .hero__shade {
    background: rgba(245, 243, 239, 0.7);
  }

  .hero__badge {
    display: none;
  }
}
</style>