<template>
  <footer class="site-footer" role="contentinfo">

    <!-- ── Top: brand + navegación ── -->
    <div class="footer__top">
      <div class="container footer__top-inner">

        <!-- Brand -->
        <div class="footer__brand">
          <a href="#inicio" class="footer__logo" aria-label="REY ROSES — Ir al inicio">
            <!--
              Logo: public/images/logo.png
              Si tienes el logo en negro usa esta imagen.
              Cambia el src si el archivo tiene otro nombre.
            -->
            <img src="/images/logo.png" alt="REY ROSES" class="footer__logo-img" width="140" height="42" loading="lazy"
              decoding="async" />
          </a>
          <p class="footer__tagline">
            Rosas ecuatorianas de altura premium.<br />
            Cultivo, pasión y excelencia desde los Andes.
          </p>

          <!-- Redes sociales con iconos SVG -->
          <nav class="footer__socials" aria-label="Redes sociales">
            <a v-for="s in SOCIALS" :key="s.name" :href="s.href" class="footer__soc-link" :aria-label="s.name"
              target="_blank" rel="noopener noreferrer" v-html="s.icon"></a>
          </nav>
        </div>

        <!-- Navegación -->
        <nav class="footer__nav" aria-label="Navegación de pie de página">
          <div v-for="group in NAV_GROUPS" :key="group.title" class="footer__nav-group">
            <h3 class="footer__nav-title">{{ group.title }}</h3>
            <ul role="list" class="footer__nav-links">
              <li v-for="link in group.links" :key="link.label">
                <a :href="link.href">{{ link.label }}</a>
              </li>
            </ul>
          </div>
        </nav>

      </div>
    </div>

  
    <div class="footer__bottom">
      <div class="container footer__bottom-inner">
        <p class="footer__copy">
          &copy; {{ year }} REY RUSES S.A.S. — Todos los derechos reservados.
        </p>
        <div class="footer__legal" role="list">
          <a href="#" role="listitem">Privacidad</a>
          <span aria-hidden="true">·</span>
          <a href="#" role="listitem">Términos</a>
        </div>
      </div>
    </div>

  </footer>
</template>

<script setup>
import { computed } from 'vue'

// ── Año dinámico ─────────────────────────────────────────────────────────────
const year = computed(() => new Date().getFullYear())

// ── Redes sociales con iconos SVG inline ─────────────────────────────────────
const SOCIALS = [
  {
    name: 'Instagram',
    href: 'https://www.instagram.com/riccroses.pe/',
    icon: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.8"
             stroke-linecap="round" stroke-linejoin="round"
             aria-hidden="true">
             <rect x="2" y="2" width="20" height="20" rx="5"/>
             <circle cx="12" cy="12" r="4"/>
             <circle cx="17.5" cy="6.5" r=".6" fill="currentColor" stroke="none"/>
           </svg>`,
  },
  {
    name: 'TikTok',
    href: 'https://www.tiktok.com/@riccroses',
    icon: `<svg width="15" height="15" viewBox="0 0 24 24"
             fill="currentColor" aria-hidden="true">
             <path d="M12 2h3.5c.1 1.2.6 2.3 1.4 3.2a5.7 5.7 0 002.9 1.6v3.6a9.3 9.3 0 01-4.3-1.2v7.2a6.7 6.7 0 11-6.7-6.7h2.7v3.5a3.2 3.2 0 00-2.3-.9 3.2 3.2 0 00-3.2 3.2 3.2 3.2 0 003.2 3.2c1.8 0 3.2-1.4 3.2-3.2V2z"/>
           </svg>`,
  },

  {
    name: 'Facebook',
    href: 'https://www.facebook.com/ReyRosesPeru',
    icon: `<svg width="15" height="15" viewBox="0 0 24 24"
             fill="currentColor" aria-hidden="true">
             <path d="M22 12c0-5.5-4.5-10-10-10S2 6.5 2 12c0 5 3.7 9.1 8.5 9.9v-7h-2.6v-2.9h2.6V9.8c0-2.6 1.5-4 3.8-4 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6v1.9h2.8l-.4 2.9h-2.4v7C18.3 21.1 22 17 22 12z"/>
           </svg>`,
  },


  {
    name: 'WhatsApp',
    href: 'https://wa.me/51983015624',
    icon: `<svg width="15" height="15" viewBox="0 0 24 24"
             fill="currentColor" aria-hidden="true">
             <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
           </svg>`,
  },
]

// ── Grupos de navegación ──────────────────────────────────────────────────────
// Para añadir o quitar links, edita solo este array.
const NAV_GROUPS = [
  {
    title: 'Empresa',
    links: [
      { label: 'Nosotros', href: '#nosotros' },
      { label: 'Productos', href: '#productos' },
      { label: 'Certificaciones', href: '#nosotros' },
      { label: 'Sostenibilidad', href: '#nosotros' },
    ],
  },
  {
    title: 'Exportaciones',
    links: [
      { label: 'Distribuidores', href: '#contacto' },
      { label: 'Importadores', href: '#contacto' },
      { label: 'Florerías', href: '#contacto' },
      { label: 'Catálogo PDF', href: '#contacto' },
    ],
  },
  {
    title: 'Contacto',
    links: [
      { label: 'reyruses@gmail.com', href: 'mailto:reyruses@gmail.com' },
      { label: '+51 983 015 624', href: 'tel:+51983015624' },
      { label: 'Chiclayo, Perú', href: '#contacto' },
      { label: 'Lun–Vie 08:00–17:00', href: '#contacto' },
    ],
  },
]
</script>

<style scoped>
/* ═══════════════════════════════════════════════════════
   FOOTER — variables locales
   ═══════════════════════════════════════════════════════ */
.site-footer {
  --footer-bg: #dedcd6;
  /* gris muy claro cálido */
  --footer-bg-bt: #ECEAE5;
  /* gris un poco más oscuro para el bottom */
  --footer-border: rgba(0, 0, 0, 0.07);
  --footer-text: rgba(0, 0, 0, 0.45);
  --footer-text-dim: rgba(0, 0, 0, 0.28);
  --footer-hover: var(--or);

  background: var(--footer-bg);
  color: var(--footer-text);
}

/* ═══════════════════════════════════════════════════════
   TOP — brand + navegación
   ═══════════════════════════════════════════════════════ */
.footer__top {
  border-bottom: 1px solid var(--footer-border);
}

.footer__top-inner {
  display: grid;
  grid-template-columns: 1fr;
  gap: 3rem;
  padding-block: clamp(3rem, 6vw, 5rem);
}

@media (min-width: 768px) {
  .footer__top-inner {
    grid-template-columns: 5fr 7fr;
    gap: 5rem;
  }
}

/* ── Brand ── */
.footer__brand {
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

.footer__logo {
  display: inline-flex;
  transition: opacity var(--transition-fast);
}

.footer__logo:hover {
  opacity: 0.7;
}

.footer__logo-img {
  height: 38px;
  width: auto;
  object-fit: contain;
  /* Imagen en negro — legible sobre fondo claro */
  display: block;
}

.footer__tagline {
  font-size: 0.76rem;
  color: var(--footer-text);
  line-height: 1.8;
  max-width: 240px;
}

/* Redes sociales */
.footer__socials {
  display: flex;
  gap: 0.5rem;
}

.footer__soc-link {
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid var(--footer-border);
  border-radius: var(--radius-md);
  color: var(--footer-text);
  background: transparent;
  transition:
    border-color var(--transition-fast),
    color var(--transition-fast),
    background var(--transition-fast);
}

.footer__soc-link:hover {
  border-color: var(--or);
  color: var(--or);
  background: var(--orb);
}

/* ── Navegación ── */
.footer__nav {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2rem;
}

@media (max-width: 480px) {
  .footer__nav {
    grid-template-columns: repeat(2, 1fr);
  }
}

.footer__nav-title {
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 0.22em;
  text-transform: uppercase;
  color: var(--bk);
  margin-bottom: 1rem;
  padding-bottom: 0.65rem;
  border-bottom: 1.5px solid var(--or);
  /* La línea naranja debajo del título da jerarquía sin ser recargado */
  width: fit-content;
}

.footer__nav-links {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.footer__nav-links a {
  font-size: 0.74rem;
  color: var(--footer-text);
  transition: color var(--transition-fast);
  text-decoration: none;
}

.footer__nav-links a:hover {
  color: var(--or);
}

/* ═══════════════════════════════════════════════════════
   BOTTOM — copyright + legales
   ═══════════════════════════════════════════════════════ */
.footer__bottom {
  background: var(--footer-bg);
  border-top: 1px solid var(--footer-border);
}

.footer__bottom-inner {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  text-align: center;
  padding-block: 1.2rem;
}

@media (min-width: 640px) {
  .footer__bottom-inner {
    flex-direction: row;
    justify-content: space-between;
    text-align: left;
  }
}

.footer__copy {
  font-size: 0.62rem;
  color: var(--footer-text-dim);
  letter-spacing: 0.04em;
}

.footer__legal {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  font-size: 0.62rem;
  color: var(--footer-text-dim);
}

.footer__legal a {
  color: var(--footer-text-dim);
  text-decoration: none;
  transition: color var(--transition-fast);
}

.footer__legal a:hover {
  color: var(--or);
}

/* ═══════════════════════════════════════════════════════
   ACCESIBILIDAD — reducción de movimiento
   ═══════════════════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {

  .footer__logo,
  .footer__soc-link,
  .footer__nav-links a,
  .footer__legal a {
    transition: none;
  }
}
</style>