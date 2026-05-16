<template>
  <section id="nosotros" class="about" aria-labelledby="about-heading" ref="sectionRef">

    <!-- ── Panel izquierdo: imagen ── -->
    <div class="about__panel-img">
      <figure class="about__fig">
        <img
          src="/images/about.png"
          alt="Equipo REY ROSES trabajando en la finca a 2.800 metros de altitud en los Andes ecuatorianos"
          class="about__img"
          loading="lazy"
          decoding="async"
          width="900"
          height="1200"
        />
        <div class="about__img-shade" aria-hidden="true"></div>
      </figure>

      <!-- Chip: años de experiencia -->
      <div class="about__chip" aria-label="Más de 15 años de experiencia">
        <strong class="chip__num">15<span aria-hidden="true">+</span></strong>
        <span class="chip__label">Años cultivando</span>
      </div>
    </div>

    <!-- ── Panel derecho: contenido ── -->
    <div class="about__panel-content">

      <!-- Encabezado -->
      <div class="about__header" data-reveal>
        <p class="about__label">Nuestra Historia</p>
        <h2 id="about-heading" class="about__heading">
          Cada rosa cuenta<br />
          <em>una historia.</em>
        </h2>
      </div>

      <!-- Lead -->
      <p class="about__lead" data-reveal>
        Desde los Andes ecuatorianos en Ecuador hasta los hogares y negocios más
        exigentes de Perú, REY ROSES cultiva y distribuye rosas que transmiten
        emociones auténticas. Nuestra cadena de producción y logística asegura que
        cada flor conserve su frescura y belleza al llegar a destino. Hoy, nuestras
        rosas llegan a todo Chiclayo, llevando consigo la esencia de la tierra
        andina y el compromiso con la calidad que nos distingue. No entregamos
        simples flores — compartimos experiencias que acompañan momentos únicos y
        memorables.
      </p>

      <!-- Estadísticas -->
      <dl class="about__stats" aria-label="Cifras de la empresa" data-reveal>
        <div
          v-for="stat in STATS"
          :key="stat.label"
          class="about__stat"
        >
          <dd class="stat__num">{{ stat.value }}</dd>
          <dt class="stat__label">{{ stat.label }}</dt>
        </div>
      </dl>

      <!-- Divisor -->
      <div class="about__divider" aria-hidden="true">
        <span class="divider__line"></span>
        <span class="divider__dot"></span>
        <span class="divider__line"></span>
      </div>

      <!-- Pilares -->
      <ul class="about__pillars" aria-label="Nuestros pilares" data-reveal>
        <li
          v-for="pillar in PILLARS"
          :key="pillar.title"
          class="pillar"
        >
          <div class="pillar__icon" aria-hidden="true">{{ pillar.icon }}</div>
          <div class="pillar__body">
            <h3 class="pillar__title">{{ pillar.title }}</h3>
            <p class="pillar__desc">{{ pillar.desc }}</p>
          </div>
        </li>
      </ul>

      <!-- CTA -->
      <a href="#contacto" class="about__cta" data-reveal>
        Habla con nuestro equipo
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path
            d="M2 7h10M7 2l5 5-5 5"
            stroke="currentColor"
            stroke-width="1.6"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </a>

    </div>

  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useScrollReveal } from '@/composables/useScrollReveal.js'

// ── Scroll reveal ────────────────────────────────────────────────────────────
const sectionRef = ref(null)
const { revealAll } = useScrollReveal()
onMounted(() => revealAll(sectionRef.value))

// ── Datos ────────────────────────────────────────────────────────────────────
const STATS = [
  { value: '100%', label: 'Pedidos personalizados' },
  { value: '100%', label: 'Cobertura en Chiclayo' },
  { value: '20+',  label: 'Variedades exclusivas' },
  { value: '100%', label: 'Calidad garantizada'  },
]

const PILLARS = [
  {
    icon: '🚚',
    title: 'Distribución directa',
    desc: 'Entregamos rosas frescas desde Ecuador hacia Perú, con cobertura completa en Chiclayo.',
  },
  {
    icon: '🏬',
    title: 'Presencia local',
    desc: 'Estamos cerca de ti, con atención personalizada en todo Chiclayo.',
  },
  {
    icon: '💬',
    title: 'Atención inmediata',
    desc: 'Pedidos y consultas por WhatsApp para una experiencia rápida y confiable.',
  },
]

</script>

<style scoped>
/* ═══════════════════════════════════════════════════════
   ABOUT — layout split de dos paneles
   ═══════════════════════════════════════════════════════ */
.about {
  display: grid;
  grid-template-columns: 1fr; /* Mobile: una columna */
}

@media (min-width: 900px) {
  .about {
    grid-template-columns: 1fr 1fr; /* Desktop: 50/50 */
    align-items: stretch;           /* ambos paneles misma altura */
  }
}

/* ═══════════════════════════════════════════════════════
   PANEL IZQUIERDO — imagen
   ═══════════════════════════════════════════════════════ */
.about__panel-img {
  position: relative;
  overflow: hidden;
  /* Mobile: altura proporcional */
  height: 60vw;
  max-height: 480px;
}

@media (min-width: 900px) {
  .about__panel-img {
    /* Desktop: la imagen se estira para igualar la altura del contenido */
    height: auto;
    max-height: none;
  }
}

.about__fig {
  width: 100%;
  height: 100%;
}

.about__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
  transition: transform 0.8s ease;
}

.about__panel-img:hover .about__img {
  transform: scale(1.03);
}

/* Overlay de gradiente */
.about__img-shade {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    160deg,
    rgba(0, 0, 0, 0.05) 0%,
    rgba(0, 0, 0, 0.4) 100%
  );
  pointer-events: none;
}

/* ── Chip flotante ── */
.about__chip {
  position: absolute;
  bottom: 1.75rem;
  left: 1.75rem;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 0.1rem;
  background: var(--or);
  color: var(--wh);
  padding: 0.9rem 1.2rem;
  border-radius: var(--radius-md);
  box-shadow: 0 8px 28px rgba(212, 131, 10, 0.45);
}

.chip__num {
  font-family: var(--font-display);
  font-size: 2.4rem;
  font-weight: 900;
  line-height: 1;
}

.chip__num span {
  font-size: 1.4rem;
}

.chip__label {
  font-size: 0.56rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  opacity: 0.85;
}

/* ═══════════════════════════════════════════════════════
   PANEL DERECHO — contenido normal (sin scroll propio)
   ═══════════════════════════════════════════════════════ */
.about__panel-content {
  background: var(--wh3);
  padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1.5rem, 5vw, 3.5rem);
  display: flex;
  flex-direction: column;
  gap: 2.2rem;
  /* Sin overflow-y: auto — el scroll es el del page normal */
}

/* ── Encabezado ── */
.about__header {
  display: flex;
  flex-direction: column;
  gap: 0.7rem;
}

.about__label {
  font-size: 0.56rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: var(--or);
}

.about__heading {
  font-family: var(--font-display);
  font-size: clamp(2.2rem, 4vw, 3.2rem);
  font-weight: 900;
  line-height: 1.05;
  color: var(--bk);
}

.about__heading em {
  font-style: italic;
  font-weight: 400;
  color: var(--or);
}

/* ── Lead ── */
.about__lead {
  font-size: clamp(0.88rem, 1.4vw, 1rem);
  color: var(--gr);
  line-height: 1.9;
  border-left: 2px solid var(--or);
  padding-left: 1.1rem;
}

/* ── Estadísticas ── */
.about__stats {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0;
  border: 1.5px solid var(--gr3);
  border-radius: var(--radius-lg);
  overflow: hidden;
  background: var(--wh);
}

.about__stat {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 1.2rem 1rem;
  border-right: 1.5px solid var(--gr3);
  border-bottom: 1.5px solid var(--gr3);
  transition: background var(--transition-fast);
  cursor: default;
}

.about__stat:nth-child(2n)          { border-right: none; }
.about__stat:nth-last-child(-n + 2) { border-bottom: none; }

.about__stat:hover { background: var(--orb); }

.stat__num {
  font-family: var(--font-display);
  font-size: 1.8rem;
  font-weight: 900;
  color: var(--or);
  line-height: 1;
}

.stat__label {
  font-size: 0.56rem;
  font-weight: 500;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--gr2);
}

/* ── Divisor ── */
.about__divider {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.divider__line {
  flex: 1;
  height: 1px;
  background: var(--gr3);
}

.divider__dot {
  width: 6px;
  height: 6px;
  background: var(--or);
  border-radius: 50%;
  flex-shrink: 0;
}

/* ── Pilares ── */
.about__pillars {
  display: flex;
  flex-direction: column;
  gap: 1.4rem;
}

.pillar {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.pillar__icon {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  background: var(--wh);
  border-radius: var(--radius-md);
  border: 1.5px solid rgba(212, 131, 10, 0.18);
}

.pillar__title {
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--bk);
  letter-spacing: 0.02em;
  margin-bottom: 0.3rem;
}

.pillar__desc {
  font-size: 0.76rem;
  color: var(--gr);
  line-height: 1.75;
}

/* ── CTA ── */
.about__cta {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  align-self: flex-start;
  font-size: 0.66rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  background: var(--or);
  color: var(--wh);
  padding: 11px 22px;
  border-radius: var(--radius-sm);
  box-shadow: 0 4px 16px rgba(212, 131, 10, 0.28);
  transition:
    background  var(--transition-fast),
    transform   var(--transition-fast),
    box-shadow  var(--transition-fast);
}

.about__cta:hover {
  background: var(--or2);
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(212, 131, 10, 0.38);
}

.about__cta:active {
  transform: translateY(0);
}

/* ═══════════════════════════════════════════════════════
   ACCESIBILIDAD — reducción de movimiento
   ═══════════════════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {
  .about__img,
  .about__cta,
  .about__stat {
    transition: none;
  }
}
</style>