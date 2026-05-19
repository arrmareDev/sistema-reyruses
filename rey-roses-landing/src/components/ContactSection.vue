<template>
  <section
    id="contacto"
    class="contact"
    aria-labelledby="contact-heading"
    ref="sectionRef"
  >

    <!-- ── Banda naranja superior ── -->
    <div class="contact__band">
      <div class="contact__band-left">
        <p class="contact__eyebrow">Contáctanos</p>
        <h2 id="contact-heading" class="contact__title">
          Tu visión.<br />
          <em>Nuestras rosas.</em>
        </h2>
      </div>
      <p class="contact__band-desc">
        Trabajamos con importadores, distribuidores y florerías de todo el mundo.
        Te respondemos en menos de 24 horas.
      </p>
    </div>

    <!-- ── Cuerpo: info + formulario ── -->
    <div class="contact__body">

      <!-- ── Panel izquierdo: información ── -->
      <aside class="contact__info" aria-label="Información de contacto">
        <div class="contact__info-header">
          <p class="contact__info-eyebrow">Información</p>
          <p class="contact__info-sub">
            Estamos listos para atenderte y hacer llegar nuestras rosas
            a donde las necesitas.
          </p>
        </div>
        <ul class="contact__info-list" role="list">
          <li
            v-for="item in INFO"
            :key="item.label"
            class="info-card"
          >
            <div class="info-card__icon" aria-hidden="true" v-html="item.icon"></div>
            <div class="info-card__text">
              <span class="info-card__label">{{ item.label }}</span>
              <span class="info-card__value">{{ item.value }}</span>
            </div>
          </li>
        </ul>
      </aside>

      <!-- ── Panel derecho: formulario ── -->
      <div class="contact__form-wrap" data-reveal>
        <Transition name="fade" mode="out-in">

          <!-- Estado éxito -->
          <div
            v-if="status.success"
            class="contact__success"
            role="alert"
            aria-live="polite"
            key="ok"
          >
            <svg width="52" height="52" viewBox="0 0 52 52" fill="none" aria-hidden="true">
              <circle cx="26" cy="26" r="25" stroke="#D4830A" stroke-width="1.5"/>
              <path d="M16 26l7 7 13-14" stroke="#D4830A" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="success__title">¡Mensaje enviado!</h3>
            <p class="success__desc">
              Gracias por contactarnos. Nuestro equipo se comunicará contigo en breve.
            </p>
            <button class="btn-reset" @click="reset">Enviar otro mensaje</button>
          </div>

          <!-- Formulario -->
          <form
            v-else
            class="form"
            novalidate
            key="form"
            @submit.prevent="handleSubmit"
            aria-label="Formulario de contacto REY ROSES"
          >
            <!-- Header del formulario -->
            <div class="form__header">
              <h3 class="form__title">Consulta sobre tu pedido</h3>
              <p class="form__sub">Te responderemos en menos de 24 horas hábiles.</p>
            </div>

            <!-- Nombre + Correo -->
            <div class="f-row">
              <div class="f-fld" :class="{ error: fields.name.touched && fields.name.error }">
                <label for="c-name" class="f-lbl">
                  Nombre <span class="req" aria-label="requerido">*</span>
                </label>
                <input
                  id="c-name"
                  v-model="fields.name.value"
                  type="text"
                  class="f-inp"
                  placeholder="Tu nombre completo"
                  autocomplete="name"
                  maxlength="100"
                  :aria-invalid="fields.name.touched && !!fields.name.error"
                  :aria-describedby="fields.name.error ? 'err-name' : undefined"
                  @blur="touchField('name')"
                  @input="fields.name.touched && validateField('name')"
                />
                <p v-if="fields.name.touched && fields.name.error" id="err-name" class="f-err" role="alert">
                  {{ fields.name.error }}
                </p>
              </div>

              <div class="f-fld" :class="{ error: fields.email.touched && fields.email.error }">
                <label for="c-email" class="f-lbl">
                  Correo <span class="req" aria-label="requerido">*</span>
                </label>
                <input
                  id="c-email"
                  v-model="fields.email.value"
                  type="email"
                  class="f-inp"
                  placeholder="correo@empresa.com"
                  autocomplete="email"
                  maxlength="150"
                  :aria-invalid="fields.email.touched && !!fields.email.error"
                  :aria-describedby="fields.email.error ? 'err-email' : undefined"
                  @blur="touchField('email')"
                  @input="fields.email.touched && validateField('email')"
                />
                <p v-if="fields.email.touched && fields.email.error" id="err-email" class="f-err" role="alert">
                  {{ fields.email.error }}
                </p>
              </div>
            </div>

            <!-- Teléfono -->
            <div class="f-fld">
              <label for="c-phone" class="f-lbl">
                Teléfono <span class="opt">(opcional)</span>
              </label>
              <input
                id="c-phone"
                v-model="fields.phone.value"
                type="tel"
                class="f-inp"
                placeholder="+593 999 999 999"
                autocomplete="tel"
                maxlength="30"
                @blur="touchField('phone')"
                @input="fields.phone.touched && validateField('phone')"
              />
              <p v-if="fields.phone.touched && fields.phone.error" class="f-err" role="alert">
                {{ fields.phone.error }}
              </p>
            </div>

            <!-- Mensaje -->
            <div class="f-fld" :class="{ error: fields.message.touched && fields.message.error }">
              <label for="c-msg" class="f-lbl">
                Mensaje <span class="req" aria-label="requerido">*</span>
              </label>
              <textarea
                id="c-msg"
                v-model="fields.message.value"
                class="f-inp f-ta"
                rows="5"
                placeholder="Cuéntanos tu proyecto, volumen o consulta..."
                maxlength="500"
                :aria-invalid="fields.message.touched && !!fields.message.error"
                :aria-describedby="fields.message.error ? 'err-msg' : undefined"
                @blur="touchField('message')"
                @input="fields.message.touched && validateField('message')"
              ></textarea>
              <div class="f-cnt" aria-live="polite">
                {{ fields.message.value.length }} / 500
              </div>
              <p v-if="fields.message.touched && fields.message.error" id="err-msg" class="f-err" role="alert">
                {{ fields.message.error }}
              </p>
            </div>

            <!-- Error global -->
            <p v-if="status.error" class="f-err-global" role="alert">
              Ocurrió un error. Por favor inténtalo de nuevo.
            </p>

            <!-- Submit -->
            <button
              type="submit"
              class="btn-sub"
              :disabled="status.loading"
              :aria-busy="status.loading.toString()"
            >
              <span v-if="status.loading" class="spinner" aria-hidden="true"></span>
              {{ status.loading ? 'Enviando...' : 'Enviar Mensaje' }}
            </button>

            <!-- Nota de privacidad -->
            <p class="form__note">
              Tus datos están seguros y nunca serán compartidos con terceros.
            </p>

          </form>
        </Transition>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useForm }         from '@/composables/useForm.js'
import { useScrollReveal } from '@/composables/useScrollReveal.js'

// ── Scroll reveal ────────────────────────────────────────────────────────────
const sectionRef = ref(null)
const { revealAll } = useScrollReveal()
onMounted(() => revealAll(sectionRef.value))

// ── Formulario ───────────────────────────────────────────────────────────────
const { fields, status, touchField, validateField, handleSubmit, reset } = useForm()

// ── Datos de contacto con iconos SVG ─────────────────────────────────────────
const INFO = [
  {
    label: 'Ubicación',
    value: 'Chiclayo - Perú',
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.6"
             stroke-linecap="round" stroke-linejoin="round">
             <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
             <circle cx="12" cy="9" r="2.5"/>
           </svg>`,
  },
  {
    label: 'Email',
    value: 'reyruses@gmail.com',
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.6"
             stroke-linecap="round" stroke-linejoin="round">
             <rect x="2" y="4" width="20" height="16" rx="2"/>
             <path d="M2 7l10 7 10-7"/>
           </svg>`,
  },
  {
    label: 'Teléfono',
    value: '+51 983 015 624',
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.6"
             stroke-linecap="round" stroke-linejoin="round">
             <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07
                      A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.01 1.16
                      A2 2 0 012 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81
                      a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27
                      a2 2 0 012.11-.45 12.84 12.84 0 002.81.7
                      A2 2 0 0122 14.1v2.82z"/>
           </svg>`,
  },
  {
    label: 'Horario',
    value: 'Lun – Vie, 08:00 – 17:00 GMT-5',
    icon: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.6"
             stroke-linecap="round" stroke-linejoin="round">
             <circle cx="12" cy="12" r="10"/>
             <path d="M12 6v6l4 2"/>
           </svg>`,
  },
]
</script>

<style scoped>
/* ═══════════════════════════════════════════════════════
   CONTACT — sección general
   ═══════════════════════════════════════════════════════ */
.contact {
  background: var(--wh);
  overflow: hidden;
}

/* ═══════════════════════════════════════════════════════
   BANDA NARANJA SUPERIOR
   ═══════════════════════════════════════════════════════ */
.contact__band {
  background: var(--or);
  padding: clamp(2rem, 5vw, 3rem) clamp(1.5rem, 5vw, 4rem);
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

@media (min-width: 768px) {
  .contact__band {
    flex-direction: row;
    align-items: flex-end;
    justify-content: space-between;
    gap: 3rem;
  }
}

.contact__band-left {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.contact__eyebrow {
  font-size: 0.56rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.6);
}

.contact__title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 4vw, 3.2rem);
  font-weight: 900;
  color: var(--wh);
  line-height: 1.05;
}

.contact__title em {
  font-style: italic;
  font-weight: 400;
  opacity: 0.85;
}

.contact__band-desc {
  font-size: clamp(0.82rem, 1.3vw, 0.95rem);
  color: rgba(255, 255, 255, 0.75);
  line-height: 1.85;
  max-width: 380px;
}

@media (min-width: 768px) {
  .contact__band-desc {
    text-align: right;
    align-self: flex-end;
  }
}

/* ═══════════════════════════════════════════════════════
   CUERPO — dos paneles
   ═══════════════════════════════════════════════════════ */
.contact__body {
  display: grid;
  grid-template-columns: 1fr;
}

@media (min-width: 900px) {
  .contact__body {
    grid-template-columns: 1fr 1.8fr;
  }
}

/* ═══════════════════════════════════════════════════════
   PANEL IZQUIERDO — información
   ═══════════════════════════════════════════════════════ */
.contact__info {
  background: var(--wh3);
  border-right: 1px solid var(--gr3);
  padding: clamp(2rem, 5vw, 3.5rem) clamp(1.5rem, 4vw, 3rem);
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
  font-style: normal;
}

.contact__info-header {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.contact__info-eyebrow {
  font-size: 0.54rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: var(--or);
}

.contact__info-sub {
  font-size: 0.82rem;
  color: var(--gr);
  line-height: 1.8;
}

.contact__info-list {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.info-card {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.2rem 0;
  border-bottom: 1px solid var(--gr3);
}

.info-card:first-child {
  border-top: 1px solid var(--gr3);
}

.info-card__icon {
  flex-shrink: 0;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--orb);
  border-radius: var(--radius-md);
  color: var(--or);
  border: 1px solid rgba(212, 131, 10, 0.18);
}

.info-card__text {
  display: flex;
  flex-direction: column;
  gap: 3px;
  padding-top: 3px;
}

.info-card__label {
  font-size: 0.54rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  color: var(--gr2);
}

.info-card__value {
  font-size: 0.84rem;
  color: var(--bk);
  font-weight: 500;
  line-height: 1.4;
}

/* ═══════════════════════════════════════════════════════
   PANEL DERECHO — formulario
   ═══════════════════════════════════════════════════════ */
.contact__form-wrap {
  background: var(--wh3);
  padding: clamp(2rem, 5vw, 3.5rem) clamp(1.5rem, 4vw, 3rem);
  display: flex;
  align-items: flex-start;
}

/* Tarjeta blanca del formulario */
.form {
  width: 100%;
  background: var(--wh);
  border-radius: 20px;
  padding: clamp(1.8rem, 4vw, 2.5rem);
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  gap: 1.2rem;
}

/* Header del form */
.form__header {
  padding-bottom: 1.4rem;
  border-bottom: 1px solid var(--gr3);
  margin-bottom: 0.2rem;
}

.form__title {
  font-family: var(--font-display);
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--bk);
  margin-bottom: 0.3rem;
}

.form__sub {
  font-size: 0.76rem;
  color: var(--gr);
}

/* ── Campos ── */
.f-row {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 560px) {
  .f-row { grid-template-columns: 1fr 1fr; }
}

.f-fld {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.f-lbl {
  font-size: 0.6rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--gr2);
}

.req  { color: var(--or); }
.opt  { font-weight: 400; opacity: 0.5; font-size: 0.58rem; }

/* Inputs con bordes redondeados */
.f-inp {
  width: 100%;
  padding: 13px 16px;
  background: var(--wh2);
  border: 1.5px solid var(--gr3);
  border-radius: 12px;
  font-family: var(--font-body);
  font-size: 0.85rem;
  color: var(--bk);
  outline: none;
  transition:
    border-color var(--transition-fast),
    box-shadow   var(--transition-fast),
    background   var(--transition-fast);
  appearance: none;
}

.f-inp::placeholder {
  color: var(--gr3);
}

.f-inp:focus {
  border-color: var(--or);
  box-shadow: 0 0 0 4px rgba(212, 131, 10, 0.08);
  background: var(--wh);
}

.error .f-inp {
  border-color: rgba(200, 60, 60, 0.5);
  box-shadow: 0 0 0 4px rgba(200, 60, 60, 0.06);
}

.f-ta {
  resize: none;
  min-height: 120px;
  line-height: 1.6;
}

.f-cnt {
  text-align: right;
  font-size: 0.58rem;
  color: var(--gr2);
  margin-top: -2px;
}

.f-err {
  font-size: 0.65rem;
  color: #c23b3b;
}

.f-err-global {
  font-size: 0.72rem;
  color: #c23b3b;
  background: rgba(200, 60, 60, 0.06);
  padding: 0.6rem 1rem;
  border-radius: 10px;
  border-left: 2px solid #c23b3b;
}

/* ── Botón submit ── */
.btn-sub {
  width: 100%;
  padding: 15px;
  cursor: pointer;
  background: var(--or);
  color: var(--wh);
  border: none;
  font-family: var(--font-body);
  font-size: 0.68rem;
  font-weight: 700;
  letter-spacing: 0.18em;
  text-transform: uppercase;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.6rem;
  box-shadow: 0 4px 20px rgba(212, 131, 10, 0.35);
  margin-top: 0.4rem;
  transition:
    background  var(--transition-fast),
    transform   var(--transition-fast),
    box-shadow  var(--transition-fast);
}

.btn-sub:hover:not(:disabled) {
  background: var(--or2);
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(212, 131, 10, 0.4);
}

.btn-sub:active:not(:disabled) { transform: translateY(0); }
.btn-sub:disabled { opacity: 0.65; cursor: not-allowed; }

.spinner {
  display: block;
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: var(--wh);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

/* Nota de privacidad */
.form__note {
  text-align: center;
  font-size: 0.65rem;
  color: var(--gr2);
}

/* ── Estado éxito ── */
.contact__success {
  width: 100%;
  background: var(--wh);
  border-radius: 20px;
  padding: 3.5rem 2rem;
  box-shadow: 0 8px 40px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 1rem;
}

.success__title {
  font-family: var(--font-display);
  font-size: 2rem;
  font-weight: 700;
  color: var(--bk);
}

.success__desc {
  font-size: 0.82rem;
  color: var(--gr);
  line-height: 1.8;
}

.btn-reset {
  display: inline-flex;
  align-items: center;
  font-family: var(--font-body);
  font-size: 0.62rem;
  font-weight: 600;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--or);
  border: 1.5px solid rgba(212, 131, 10, 0.4);
  padding: 10px 22px;
  border-radius: 10px;
  cursor: pointer;
  background: transparent;
  transition:
    background var(--transition-fast),
    color      var(--transition-fast);
}

.btn-reset:hover {
  background: var(--or);
  color: var(--wh);
}

/* ── Transición ── */
.fade-enter-active,
.fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from,
.fade-leave-to     { opacity: 0; }

/* ═══════════════════════════════════════════════════════
   ACCESIBILIDAD — reducción de movimiento
   ═══════════════════════════════════════════════════════ */
@media (prefers-reduced-motion: reduce) {
  .btn-sub,
  .btn-reset,
  .f-inp,
  .spinner {
    transition: none;
    animation: none;
  }
}
</style>