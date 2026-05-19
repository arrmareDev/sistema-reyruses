<template>
  <section id="inicio" class="hero" aria-labelledby="hero-heading">

<div class="hero__background" aria-hidden="true">
      <img src="/images/fondo-hero-rosas.jpg" 
           alt="Campo de rosas ecuatorianas" 
           class="hero__bg-img"
           loading="eager" fetchpriority="high" decoding="async" />
      
      <div class="hero__fade-gradient"></div>
    </div>

    <div class="container hero__inner">
      
      <div class="hero__content-box">
        
        <div class="hero__tag animate-fade-in" style="animation-delay: .1s">
          <span class="tag-dot" aria-hidden="true"></span>
          <span>Rosas de los Andes · Ecuador</span>
        </div>

        <h1 id="hero-heading" class="hero__title animate-fade-in-up" style="animation-delay: .2s">
          <span class="title-small">LA FLOR QUE</span><br>
          <span class="title-large">define reyes</span>
        </h1>

        <p class="hero__sub animate-fade-in-up" style="animation-delay: .42s">
          Cultivadas a 2.800 metros de altitud en los Andes ecuatorianos. 
          Luz solar intensa, tierra volcánica y el secreto de la naturaleza 
          hacen nuestras rosas incomparables.
        </p>

        <nav class="hero__btns animate-fade-in-up" style="animation-delay: .54s" aria-label="Acciones principales">
          <a href="#productos" class="btn-or">
            Ver Colección
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <a href="#nosotros" class="btn-wh">Nuestra Historia</a>
        </nav>
      </div>

    </div>

    <div class="hero__stats-box">
      <dl class="hero__stats animate-fade-in-up" style="animation-delay: .66s" aria-label="Datos de la empresa">
        <div v-for="stat in stats" :key="stat.label" class="hero__stat">
          <dt class="stat-l">{{ stat.label }}</dt>
          <dd class="stat-n">{{ stat.value }}</dd>
        </div>
      </dl>
    </div>

  </section>
</template>

<script setup>
const stats = [
  { value: '5+', label: 'Años de experiencia' },
  { value: '200+', label: 'Variedades de rosa' },
  { value: '40+', label: 'Países exportados' },
]
</script>

<style scoped>
/* ─── Hero: Layout Principal ─── */
.hero {
  position: relative;
  min-height: 100svh;
  display: flex;
  flex-direction: column;
  padding-top: var(--header-h);
  overflow: hidden;
}

/* ─── Imagen de Fondo ─── */
.hero__background {
  position: absolute;
  inset: 0;
  z-index: -1;
}

.hero__bg-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.hero__fade-gradient {
  position: absolute;
  inset: 0;
  z-index: 1;
  /* La magia: Nunca es 100% sólido y desaparece mucho antes */
  background: linear-gradient(90deg, 
    rgba(25, 40, 27, 0.95) 0%,   /* Oscuro solo en el borde izquierdo */
    rgba(25, 40, 27, 0.75) 20%,  /* Empieza a transparentarse rápido */
    rgba(25, 40, 27, 0.3) 40%,   /* Ya deja ver las rosas */
    rgba(25, 40, 27, 0) 60%);    /* Se vuelve 100% invisible mucho antes */
}

/* ─── Contenedor Interno ─── */
.hero__inner {
  position: relative;
  flex-grow: 1;
  display: flex;
  align-items: center;
  padding-top: 0;
  padding-bottom: 6rem; /* Espacio para la caja de stats flotante */
}

/* ─── Bloque de Texto Verde (Pegado a la izquierda) ─── */
.hero__content-box {
  /* Le quitamos el background-color, los bordes y las sombras */
  padding: 2rem 0; /* Solo un poco de espacio vertical */
  max-width: 600px;
  position: relative;
  z-index: 2; /* Para que el texto quede por encima del difuminado */
}

/* ─── Badge de Origen ─── */
.hero__tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: transparent;
  border: 1px solid rgba(176, 144, 93, 0.5);
  color: var(--or);
  font-size: 0.6rem;
  font-weight: 600;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  padding: 6px 14px;
  border-radius: var(--radius-sm);
  margin-bottom: 2.5rem;
}

.tag-dot {
  width: 6px;
  height: 6px;
  background: var(--or);
  border-radius: 50%;
  flex-shrink: 0;
}

/* ─── Títulos (El cambio principal) ─── */
.hero__title {
  display: flex;
  flex-direction: column;
  margin-bottom: 1.5rem;
}

.title-small {
  font-family: var(--font-display); /* Ahora es Serif */
  font-size: clamp(2rem, 4vw, 3rem); /* Mucho más grande */
  font-weight: 500;
  color: var(--or); /* DORADO */
  text-transform: uppercase;
  line-height: 1.1;
  letter-spacing: 0.02em;
}

.title-large {
  font-family: var(--font-display);
  font-size: clamp(3.5rem, 7vw, 5.5rem);
  font-weight: 400;
  font-style: italic; /* Cursiva como en el diseño */
  color: var(--wh-pure); /* BLANCO */
  line-height: 1;
}

.hero__sub {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.85);
  line-height: 1.8;
  margin-bottom: 2.5rem;
  max-width: 90%;
}

/* ─── Botones ─── */
.hero__btns {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.btn-or {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--or);
  color: var(--wh-pure);
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  padding: 14px 28px;
  border-radius: 2px;
  transition: all var(--transition-base);
}

.btn-or:hover {
  background: var(--or2);
  transform: translateY(-2px);
  box-shadow: var(--shadow-or);
}

/* Botón fantasma blanco */
.btn-wh {
  display: inline-flex;
  align-items: center;
  background: var(--wh-pure);
  color: var(--green-dark);
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  padding: 14px 28px;
  border-radius: 2px;
  transition: all var(--transition-base);
}

.btn-wh:hover {
  background: rgba(255,255,255,0.85);
  transform: translateY(-2px);
}

/* ─── Caja de Estadísticas (Ahora es flotante) ─── */
.hero__stats-box {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%); /* Centra la caja horizontalmente */
  background-color: var(--wh-pure);
  padding: 1.5rem 3rem;
  border-radius: 12px 12px 0 0; /* Bordes curvos solo arriba */
  box-shadow: var(--shadow-lg);
  z-index: 10;
  width: 90%;
  max-width: 900px; /* Evita que sea demasiado ancha en pantallas grandes */
}

.hero__stats {
  display: flex;
  justify-content: space-around; /* Reparte el espacio uniformemente */
  align-items: center;
  gap: 1rem;
}

.hero__stat {
  display: flex;
  align-items: center;
  gap: 12px;
  text-align: left;
}

.stat-n {
  font-family: var(--font-display);
  font-size: clamp(1.8rem, 3vw, 2.5rem);
  font-weight: 700;
  color: var(--or);
  line-height: 1;
}

.stat-l {
  font-size: 0.6rem;
  font-weight: 700;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: var(--bk2);
  line-height: 1.3;
}

/* ─── Responsive ─── */
@media (max-width: 900px) {
  /* 1. Dejamos que la sección crezca naturalmente sin forzar alturas */
  .hero {
    min-height: auto; 
    display: block; 
  }
  
  .hero__inner {
    padding-top: 3rem;
    padding-bottom: 7rem; /* Espacio para respirar antes de la caja blanca */
  }
  
  /* 2. El "Velo de Seda" adaptado para móvil (Sube desde abajo) */
  .hero__fade-gradient {
    background: linear-gradient(0deg, 
      rgba(25, 40, 27, 0.95) 0%,   /* Base oscura para que lean los botones */
      rgba(25, 40, 27, 0.7) 40%,   /* Se desvanece a la mitad */
      rgba(25, 40, 27, 0) 80%);    /* Arriba deja ver la flor completa */
  }

  .hero__content-box {
    padding: 0 var(--container-pad);
    max-width: 100%;
  }

  /* Ajuste tipográfico */
  .title-small { font-size: 1.4rem; }
  .title-large { font-size: 3.2rem; }

  /* 3. Botones apilados (Mejor UX para pulgares) */
  .hero__btns {
    flex-direction: column;
    width: 100%;
    gap: 0.8rem;
  }
  
  .btn-or, .btn-wh {
    width: 100%;
    justify-content: center;
  }

  /* 4. La Caja Blanca: Deja de flotar y se acomoda abajo */
  .hero__stats-box {
    position: relative; /* ¡Se acabó el overlap! */
    bottom: auto;
    left: auto;
    transform: none;
    width: 100%;
    padding: 2.5rem 2rem;
    border-radius: 24px 24px 0 0;
    margin-top: -3rem; /* Se monta un poquito en la foto para el efecto 3D */
  }

  /* 5. Estadísticas en columna centrada */
  .hero__stats {
    flex-direction: column;
    gap: 1.8rem;
  }

  .hero__stat {
    justify-content: center;
  }
}
</style>