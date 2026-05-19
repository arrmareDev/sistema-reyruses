<template>
  <section id="productos" class="products" aria-labelledby="products-heading" ref="sectionRef">
    <div class="container">

      <header class="products__head" data-reveal>
        <div class="products__head-left">
          <p class="products__eyebrow">Nuestra Colección</p>
          <h2 id="products-heading" class="products__title">
            Variedades que<br />
            <em>conquistan miradas</em>
          </h2>
        </div>
        <p class="products__subtitle">
          Más de 50 variedades seleccionadas para distribuidores,
          importadores y florerías de todo el mundo.
        </p>
      </header>

      <ul class="products__grid" role="list" aria-label="Categorías de productos REY ROSES">
        <li v-for="(category, index) in CATEGORIES" :key="category.id" class="category-card" data-reveal>
          <article class="category-card__inner">
            <figure class="category-card__fig">
              <img :src="`/images/${category.image}`" :alt="category.imageAlt" class="category-card__img" loading="lazy" decoding="async" width="600" height="800" />
              <div class="category-card__overlay" aria-hidden="true"></div>
            </figure>

            <div class="category-card__content">
              <h3 class="category-card__name">{{ category.name }}</h3>
              <div class="category-card__line" aria-hidden="true"></div>
              <p class="category-card__desc">{{ category.desc }}</p>

              <button @click="selectCategory(category.name)" class="category-card__cta" :aria-label="`Ver catálogo de ${category.name}`">
                {{ activeCategory === category.name ? 'Viendo Variedades' : 'Ver Variedades' }}
              </button>
            </div>
          </article>
        </li>
      </ul>

      <div v-if="activeCategory" class="catalog" ref="catalogRef">
        <div class="catalog__header">
          <h3>Explorando: <span>{{ activeCategory }}</span></h3>
          <button @click="activeCategory = null" class="catalog__close">Cerrar Catálogo ✕</button>
        </div>

        <ul class="catalog__grid">
          <li v-for="product in filteredProducts" :key="product.id" class="product-card">
            <div class="product-card__img-wrapper">
               <img v-if="product.image_path" :src="apiUrl + '/storage/' + product.image_path":alt="product.name" class="product-card__img" loading="lazy" />
               <div v-else class="w-full h-full bg-neutral-200 flex items-center justify-center text-xs text-neutral-500">Sin foto</div>
            </div>
            
            <div class="product-card__info">
              <h4 class="product-card__name">{{ product.name }}</h4>
              <p class="product-card__desc">{{ product.description }}</p>
              
              <p style="color: #E8751A; font-weight: bold; font-size: 0.95rem; margin-bottom: 12px;">
                Desde S/ {{ product.price_50 }}
              </p>
              
              <button class="product-card__add" @click="openStemModal(product)">
                Seleccionar Medida
              </button>
            </div>
            </li>
        </ul>
      </div>

    </div>
  </section>
  
  <button v-if="totalItems > 0" class="cart-fab" @click="isCartOpen = true" aria-label="Ver carrito">
    🛒 
    <span class="cart-fab__badge">{{ totalItems }}</span>
  </button>

  <div v-if="isCartOpen" class="cart-overlay" @click="isCartOpen = false"></div>
      
  <aside :class="['cart-panel', { 'cart-panel--open': isCartOpen }]">
    <header class="cart-panel__header">
      <h3>Carrito</h3>
      <button class="cart-panel__close" @click="isCartOpen = false">✕</button>
    </header>

    <div class="cart-panel__body">
      <ul class="cart-list" v-if="cart.length > 0">
        <li v-for="item in cart" :key="item.id" class="cart-item">
          
         <div class="cart-item__details">
    <img v-if="item.image_path" :src="apiUrl + '/storage/' + item.image_path" :alt="item.name" class="cart-item__img" />
    <div v-else class="cart-item__img bg-neutral-200 flex items-center justify-center text-[10px] text-neutral-500">Sin foto</div>
            
            <div class="cart-item__info">
              <h4>{{ item.name }}</h4>
              <p>{{ item.category ? item.category.name : 'Variedad' }}</p>
              <p style="color: #E8751A; font-weight: bold; font-size: 0.8rem;">S/ {{ item.price }}</p>
            </div>
          </div>

            <div class="cart-item__actions">
            <div class="cart-item__qty">
              <button @click="decreaseQuantity(item.id)">-</button>
              <span>{{ item.quantity }}</span>
              <button 
                @click="addToCart(item)"
                :disabled="item.quantity >= item.max_stock"
                :style="item.quantity >= item.max_stock ? 'opacity: 0.3; cursor: not-allowed; background: #e5e5e5; color: #a3a3a3;' : ''"
                :title="item.quantity >= item.max_stock ? 'Stock máximo alcanzado' : 'Agregar otro'"
              >+</button>
            </div>
            
            <button @click="removeFromCart(item.id)" class="cart-item__remove" title="Eliminar variedad">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
            </button>
          </div>
        </li>
      </ul>
      <p v-else class="cart-empty">Aún no has agregado variedades.</p>
    </div>

    <form @submit.prevent="handleCheckout" class="cart-summary" v-if="cart.length > 0">
      
      <div class="cart-summary__total">
        <span>Total a Pagar:</span>
        <span>S/ {{ cartTotal.toFixed(2) }}</span>
      </div>

      <div class="cart-summary__fields">
        <p class="cart-summary__title">Datos de Envío</p>
        <input v-model="customer.name" type="text" placeholder="Nombre completo" required />
        <input v-model="customer.phone" type="tel" placeholder="Celular de contacto" required />
        <input v-model="customer.address" type="text" placeholder="Dirección de entrega" required />
      </div>

      <button type="submit" class="cart-panel__send">
        Enviar Pedido por WhatsApp
      </button>
    </form>
  </aside>

<div v-if="showStemModal && activeProduct" class="stem-modal-overlay" @click.self="showStemModal = false">
    <div class="stem-modal">
      
      <button @click="showStemModal = false" class="stem-modal__close" title="Cerrar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
      </button>

      <div class="stem-modal__header">
<img v-if="activeProduct.image_path" :src="apiUrl + '/storage/' + activeProduct.image_path" class="stem-modal__img" />
        <div v-else class="stem-modal__img-placeholder">Sin fotografía</div>
        
        <div class="stem-modal__gradient"></div>
        
        <div class="stem-modal__badge">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 13-3-3m0 0 3-3m-3 3h8"/><path d="M4 19v-2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2"/><path d="M12 3v10"/></svg>
          Calidad Exportación
        </div>
        
        <div class="stem-modal__title-wrapper">
          <p class="stem-modal__category">{{ activeProduct.category?.name || 'Variedad' }}</p>
          <h3 class="stem-modal__name">{{ activeProduct.name }}</h3>
        </div>
      </div>

      <div class="stem-modal__body">
        
        <div class="stem-modal__stem-selection">
          <label for="stem-select" class="stem-modal__label">Selecciona la longitud de tu tallo</label>
          <div class="stem-modal__select-wrapper">
            <select 
              id="stem-select" 
              v-model="selectedStem"
              class="stem-modal__select"
            >
              <option 
                v-for="length in [50, 60, 70, 80, 90]" 
                :key="length" 
                :value="length"
                :disabled="activeProduct[`stock_${length}`] <= 0"
              >
                {{ length }}cm {{ activeProduct[`stock_${length}`] <= 0 ? '- AGOTADO' : '' }}
              </option>
            </select>
            <svg class="stem-modal__select-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
          </div>
        </div>

        <div class="stem-modal__stock-display">
          <div class="stem-modal__stock-info">
            <span class="stem-modal__stock-title">Unidades Disponibles</span>
            <span class="stem-modal__stock-subtitle">Stock actualizado en tiempo real</span>
          </div>
          <div class="stem-modal__stock-number">
            <template v-if="activeProduct[`stock_${selectedStem}`] > 0">
              <span class="stock-qty">{{ activeProduct[`stock_${selectedStem}`] }}</span>
              <span class="stock-unit">pqtes.</span>
            </template>
            <template v-else>
              <span class="stock-out">Agotado</span>
            </template>
          </div>
        </div>

        <div class="stem-modal__price-row">
          <div>
            <span class="stem-modal__price-label">Cotización Final</span>
            <p class="stem-modal__price-subtext">Precio por paquete</p>
          </div>
          <div class="stem-modal__price-value">
            <span>S/</span> {{ activeProduct[`price_${selectedStem}`] }}
          </div>
        </div>

        <button @click="confirmAddToCart" class="stem-modal__confirm">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
          Agregar al Pedido
        </button>
      </div>
    </div>
  </div>
  </template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'
import { useScrollReveal } from '@/composables/useScrollReveal.js'
import { useCart } from '@/composables/useCart.js'
import axios from 'axios'
import { toast } from 'vue-sonner'

const apiUrl = import.meta.env.VITE_API_URL;

const { cart, totalItems, cartTotal, addToCart, decreaseQuantity, removeFromCart, sendWhatsAppOrder } = useCart()
const isCartOpen = ref(false)

const customer = ref({
  name: '',
  phone: '',
  address: ''
})

const handleCheckout = () => {
  sendWhatsAppOrder(customer.value)
  isCartOpen.value = false
  customer.value = { name: '', phone: '', address: '' }
}

const sectionRef = ref(null)
const { revealAll } = useScrollReveal()

// ── Catálogo Dinámico desde Laravel ──
const apiProducts = ref([])

const fetchProducts = async () => {
  try {
    const response = await axios.get(apiUrl + '/api/products')
    apiProducts.value = response.data
  } catch (error) {
    console.error('Error cargando el catálogo:', error)
  }
}

onMounted(() => {
  revealAll(sectionRef.value)
  fetchProducts()
})

// ── Lógica de Categorías y Catálogo ──
const activeCategory = ref(null)
const catalogRef = ref(null)

const filteredProducts = computed(() => {
  if (!activeCategory.value) return []
  return apiProducts.value.filter(p => p.category && p.category.name === activeCategory.value)
})

const selectCategory = async (categoryName) => {
  activeCategory.value = categoryName
  await nextTick() 
  if (catalogRef.value) {
    catalogRef.value.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

// 👇 LÓGICA DEL MODAL DE TALLOS 👇
const showStemModal = ref(false)
const activeProduct = ref(null)
const selectedStem = ref(50) 

const openStemModal = (product) => {
  activeProduct.value = product;
  
  // Lógica inteligente: Buscar la primera medida que sí tenga stock
  const availableStems = [50, 60, 70, 80, 90];
  const firstAvailable = availableStems.find(length => product[`stock_${length}`] > 0);
  
  // Si encuentra una disponible, la selecciona. Si todas están agotadas, lo deja en 50 por defecto.
  selectedStem.value = firstAvailable !== undefined ? firstAvailable : 50; 
  
  showStemModal.value = true;
}

const confirmAddToCart = () => {
  if (!activeProduct.value) return

  // 1. Obtenemos el stock exacto de esa medida
  const stockKey = `stock_${selectedStem.value}`
  const exactStock = activeProduct.value[stockKey]

  if (exactStock <= 0) {
    toast.error('Esta medida de tallo está agotada actualmente.');
    return;
  }

  const cartItemId = `${activeProduct.value.id}-${selectedStem.value}`
  
  // 2. CANDADO: Verificamos cuántos hay ya en el carrito
  const existingItem = cart.value.find(item => item.id === cartItemId)
  if (existingItem && existingItem.quantity >= exactStock) {
    toast.error(`¡Límite alcanzado! Solo tenemos ${exactStock} paquetes en stock.`);
    return; // Detenemos la función, no agrega nada
  }

  // 3. Preparamos el item guardando su stock máximo (max_stock)
  const priceKey = `price_${selectedStem.value}`
  const exactPrice = parseFloat(activeProduct.value[priceKey])

  const cartItem = {
    ...activeProduct.value,
    id: cartItemId, 
    name: `${activeProduct.value.name} (${selectedStem.value}cm)`, 
    price: exactPrice,
    max_stock: exactStock // <--- ¡NUEVO! Guardamos el límite para el carrito
  }

  addToCart(cartItem)
  
  toast.success(`Agregado. Tienes ${existingItem ? existingItem.quantity + 1 : 1} paquete(s) en tu cotización.`)
  showStemModal.value = false 
}


const CATEGORIES = [
  {
    id: 1,
    name: 'Rosas',
    image: 'rosas.png',
    imageAlt: 'Rosas ecuatorianas',
    desc: 'Las rosas más vibrantes del mundo. Tallos fuertes, colores intensos y larga duracion de vida en florero.',
  },
  {
    id: 2,
    name: 'Garden',
    image: 'garden.png',
    imageAlt: 'Rosas tipo garden',
    desc: 'Flores de aspecto romántico y voluminoso, ideales para arreglos de alta gama y eventos exclusivos.',
  },
  {
    id: 3,
    name: 'Tinted',
    image: 'tinted.png',
    imageAlt: 'Rosas tintadas',
    desc: 'Rosas teñidas con técnica especial para lograr colores únicos. Perfectas para eventos temáticos.',
  },
]
</script>

<style scoped>
.products {
  padding: clamp(4rem, 8vw, 6rem) 0;
  background: var(--wh);
}

.products__head {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  margin-bottom: clamp(2rem, 4vw, 3rem);
  padding-bottom: clamp(2rem, 4vw, 3rem);
  border-bottom: 1px solid var(--gr3);
  align-items: end;
}

@media (min-width: 768px) {
  .products__head {
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
  }
}

.products__head-left {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
}

.products__eyebrow {
  font-size: 0.56rem;
  font-weight: 700;
  letter-spacing: 0.28em;
  text-transform: uppercase;
  color: var(--or);
}

.products__title {
  font-family: var(--font-display);
  font-size: clamp(2rem, 3.5vw, 3rem);
  font-weight: 900;
  line-height: 1.05;
  color: var(--bk);
}

.products__title em {
  font-style: italic;
  font-weight: 400;
  color: var(--or);
}

.products__subtitle {
  font-size: clamp(0.82rem, 1.3vw, 0.95rem);
  color: var(--gr);
  line-height: 1.85;
  align-self: end;
}

.products__grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

@media (min-width: 640px) {
  .products__grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1000px) {
  .products__grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.category-card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
}

.category-card__inner {
  position: relative;
  height: clamp(460px, 55vw, 580px);
}

.category-card__fig {
  position: absolute;
  inset: 0;
  margin: 0;
}

.category-card__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
  transition: transform 0.7s ease;
}

.category-card__inner:hover .category-card__img {
  transform: scale(1.04);
}

.category-card__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top,
      rgba(0, 0, 0, 0.82) 0%,
      rgba(0, 0, 0, 0.12) 50%,
      transparent 100%);
  pointer-events: none;
}

.category-card__content {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: clamp(1.4rem, 3vw, 2rem);
}

.category-card__name {
  font-family: var(--font-display);
  font-size: clamp(2rem, 3.5vw, 2.8rem);
  font-weight: 700;
  color: var(--wh);
  line-height: 1;
  letter-spacing: -0.01em;
  margin-bottom: 1rem;
}

.category-card__line {
  width: 100%;
  height: 1px;
  background: rgba(255, 255, 255, 0.2);
  margin-bottom: 1rem;
}

.category-card__desc {
  font-size: 0.86rem;
  color: rgba(255, 255, 255, 0.72);
  line-height: 1.75;
  margin-bottom: 1.4rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.category-card__cta {
  display: inline-block;
  align-self: flex-start;
  font-family: var(--font-body);
  font-size: 0.6rem;
  font-weight: 700;
  letter-spacing: 0.16em;
  text-transform: uppercase;
  text-decoration: none;
  color: var(--wh);
  border: 1.5px solid rgba(255, 255, 255, 0.55);
  padding: 11px 22px;
  border-radius: var(--radius-sm);
  transition:
    background var(--transition-fast),
    border-color var(--transition-fast),
    color var(--transition-fast),
    transform var(--transition-fast);
}

.category-card__cta:hover {
  background: var(--or);
  border-color: var(--or);
  color: var(--wh);
  transform: translateY(-2px);
  text-decoration: none;
}

.category-card__cta:active {
  transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
  .category-card__img,
  .category-card__cta {
    transition: none;
  }
}

.catalog {
  margin-top: 4rem;
  padding-top: 3rem;
  border-top: 1px solid var(--gr3);
}

.catalog__header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.catalog__header h3 {
  font-family: var(--font-display);
  font-size: 2rem;
  color: var(--bk);
}

.catalog__header span {
  color: var(--or);
  font-style: italic;
}

.catalog__close {
  background: none;
  border: none;
  color: var(--gr);
  cursor: pointer;
  font-size: 0.8rem;
  font-weight: bold;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.catalog__close:hover {
  color: var(--or);
}

.catalog__grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 2rem;
  list-style: none;
  padding: 0;
}

.product-card {
  display: flex;
  flex-direction: column;
  background: var(--wh);
  border-radius: 24px;
  padding: 14px;
  border: 2px solid #d1d5db;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); 
  transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
  border-color: var(--or);
}

.product-card__img-wrapper {
  aspect-ratio: 1 / 1;
  border-radius: 16px;
  overflow: hidden;
  background: #f5f5f5;
  margin-bottom: 1.2rem;
}

.product-card__img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.product-card:hover .product-card__img {
  transform: scale(1.05);
}

.product-card__info {
  padding: 0 0.5rem;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.product-card__name {
  font-family: var(--font-display);
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--bk);
  margin-bottom: 0.4rem;
  letter-spacing: -0.01em;
}

.product-card__desc {
  font-size: 0.85rem;
  color: var(--gr);
  margin-bottom: 1.5rem;
  line-height: 1.5;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.product-card__add {
  margin-top: auto;
  width: 100%;
  background: var(--bk);
  color: var(--wh);
  border: none;
  padding: 0.85rem;
  border-radius: 50px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.product-card__add:hover {
  background: var(--or);
  transform: scale(1.02);
}

.cart-fab {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 60px;
  height: 60px;
  background: var(--bk);
  color: var(--wh);
  border: none;
  border-radius: 50%;
  font-size: 1.5rem;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  z-index: 100;
  transition: transform var(--transition-fast);
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-fab:hover {
  transform: scale(1.05);
}

.cart-fab__badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background: var(--or);
  color: var(--wh);
  font-size: 0.75rem;
  font-weight: 700;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.cart-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.cart-panel {
  position: fixed;
  top: 0;
  right: 0;
  width: 100%;
  max-width: 400px;
  height: 100vh;
  background: var(--wh);
  box-shadow: -4px 0 24px rgba(0,0,0,0.1);
  z-index: 1000;
  display: flex;
  flex-direction: column;
  transform: translateX(100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cart-panel--open {
  transform: translateX(0);
}

.cart-panel__header {
  padding: 1.5rem;
  border-bottom: 1px solid var(--gr3);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.cart-panel__header h3 {
  font-family: var(--font-display);
  font-size: 1.5rem;
  color: var(--bk);
}

.cart-panel__close {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  color: var(--gr);
}

.cart-panel__body {
  flex-grow: 1;
  padding: 1.5rem;
  overflow-y: auto;
}

.cart-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 1rem;
  border-bottom: 1px solid var(--gr3);
}

.cart-item__info h4 {
  font-size: 1rem;
  color: var(--bk);
  margin-bottom: 0.2rem;
}

.cart-item__info p {
  font-size: 0.75rem;
  color: var(--gr);
}

.cart-item__actions {
  display: flex;
  align-items: center;
  gap: 1rem; 
}

.cart-item__qty {
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.cart-item__qty button {
  background: #f0f0f0;
  border: none;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
  color: var(--bk);
  transition: all 0.2s ease;
}

.cart-item__qty button:hover {
  background: var(--or);
  color: var(--wh);
}

.cart-item__remove {
  background: #fef2f2; 
  color: #ef4444; 
  border: none;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.cart-item__remove:hover {
  background: #ef4444;
  color: white;
  transform: scale(1.05);
}

.cart-item__actions button:hover {
  background: var(--or);
  color: var(--wh);
}

.cart-item__details {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.cart-item__img {
  width: 55px;
  height: 55px;
  object-fit: cover;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart-summary {
  padding: 1.5rem;
  background: #fafafa;
  border-top: 1px solid var(--gr3);
}

.cart-summary__total {
  display: flex;
  justify-content: space-between;
  font-family: var(--font-display);
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--bk);
  margin-bottom: 1.2rem;
}

.cart-summary__title {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  color: var(--gr);
  margin-bottom: 0.8rem;
}

.cart-summary__fields {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
  margin-bottom: 1.5rem;
}

.cart-summary__fields input {
  width: 100%;
  padding: 0.8rem 1rem;
  border: 1px solid #e5e5e5;
  border-radius: 8px;
  font-family: var(--font-body);
  font-size: 0.85rem;
  outline: none;
  background: var(--wh);
  transition: border-color 0.2s ease;
}

.cart-summary__fields input:focus {
  border-color: var(--or);
}

.cart-panel__send {
  width: 100%;
  background: #25D366; 
  color: var(--wh);
  border: none;
  padding: 1rem;
  font-family: var(--font-body);
  font-weight: 700;
  font-size: 0.9rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background var(--transition-fast);
}

.cart-panel__send:hover {
  background: #128C7E;
}

.stem-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15, 15, 15, 0.85);
  backdrop-filter: blur(8px);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.stem-modal {
  background: var(--wh);
  border-radius: 28px;
  width: 100%;
  max-width: 420px;
  overflow: hidden;
  position: relative;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255,255,255,0.1);
  animation: modalPop 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes modalPop {
  0% { opacity: 0; transform: translateY(30px) scale(0.95); }
  100% { opacity: 1; transform: translateY(0) scale(1); }
}

.stem-modal__close {
  position: absolute;
  top: 1.2rem;
  right: 1.2rem;
  width: 36px;
  height: 36px;
  background: rgba(0, 0, 0, 0.2);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.1);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 10;
  transition: all 0.3s ease;
}

.stem-modal__close:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: rotate(90deg);
}

.stem-modal__header {
  height: 240px;
  position: relative;
  background: #f5f5f5;
}

.stem-modal__img, .stem-modal__img-placeholder {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.stem-modal__img-placeholder {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
  font-weight: 500;
}

.stem-modal__gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
}

.stem-modal__badge {
  position: absolute;
  top: 1.2rem;
  left: 1.2rem;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  padding: 6px 12px;
  border-radius: 20px;
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  display: flex;
  align-items: center;
  gap: 6px;
}

.stem-modal__title-wrapper {
  position: absolute;
  bottom: 1.5rem;
  left: 1.5rem;
  color: white;
}

.stem-modal__category {
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  color: var(--or);
  margin-bottom: 0.3rem;
}

.stem-modal__name {
  font-family: var(--font-display);
  font-size: 2.2rem;
  font-weight: 900;
  line-height: 1.1;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.stem-modal__body {
  padding: 2rem 1.5rem;
}

.stem-modal__price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f9f9fa;
  padding: 1.2rem;
  border-radius: 20px;
  margin-bottom: 1.5rem;
  border: 1px solid #f0f0f0;
}

.stem-modal__price-label {
  font-size: 0.75rem;
  font-weight: 800;
  color: var(--bk);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: block;
}

.stem-modal__price-subtext {
  font-size: 0.65rem;
  color: var(--gr);
  margin-top: 2px;
}

.stem-modal__price-value {
  font-size: 2.2rem;
  font-weight: 900;
  color: var(--or);
  line-height: 1;
  display: flex;
  align-items: flex-start;
  gap: 4px;
}

.stem-modal__price-value span {
  font-size: 1rem;
  margin-top: 4px;
  color: var(--bk);
}

.stem-modal__confirm {
  width: 100%;
  background: var(--bk);
  color: white;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-size: 0.9rem;
  padding: 1.2rem;
  border: none;
  border-radius: 18px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stem-modal__confirm:hover {
  background: var(--or);
  transform: translateY(-2px);
  box-shadow: 0 12px 25px rgba(232, 117, 26, 0.3);
}

/* --- Nuevo Diseño del Modal (Selector Desplegable y Stock) --- */

.stem-modal__stem-selection {
  margin-bottom: 1.5rem;
}

.stem-modal__label {
  display: block;
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--gr);
  margin-bottom: 0.8rem;
}

.stem-modal__select-wrapper {
  position: relative;
  width: 100%;
}

.stem-modal__select {
  width: 100%;
  appearance: none; 
  -webkit-appearance: none;
  background-color: #f9f9fa;
  border: 2px solid #f0f0f0;
  border-radius: 16px;
  padding: 1.2rem 1.5rem;
  font-size: 1.1rem;
  font-weight: 800;
  color: var(--bk);
  cursor: pointer;
  outline: none;
  transition: all 0.3s ease;
}

.stem-modal__select:focus, .stem-modal__select:hover {
  border-color: var(--or);
  box-shadow: 0 4px 12px rgba(232, 117, 26, 0.1);
}

.stem-modal__select-icon {
  position: absolute;
  right: 1.5rem;
  top: 50%;
  transform: translateY(-50%);
  width: 24px;
  height: 24px;
  color: var(--or);
  pointer-events: none; 
}

/* --- Panel de Stock Verde --- */
.stem-modal__stock-display {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #f0fdf4; 
  border: 1px solid #bbf7d0;
  border-radius: 18px;
  padding: 1.2rem 1.5rem;
  margin-bottom: 2rem;
}

.stem-modal__stock-info {
  display: flex;
  flex-direction: column;
}

.stem-modal__stock-title {
  font-size: 0.75rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #166534; 
}

.stem-modal__stock-subtitle {
  font-size: 0.65rem;
  color: #22c55e;
  margin-top: 4px;
}

.stem-modal__stock-number {
  display: flex;
  align-items: baseline;
  gap: 4px;
}

.stock-qty {
  font-size: 2.8rem;
  font-weight: 900;
  color: #10b981; 
  line-height: 1;
}

.stock-unit {
  font-size: 0.85rem;
  font-weight: 800;
  color: #10b981;
  text-transform: uppercase;
}

.stock-out {
  font-size: 1.5rem;
  font-weight: 900;
  color: #ef4444; 
  text-transform: uppercase;
}

@media (max-width: 640px) {
  .stem-modal__header {
    height: 160px; 
  }
  
  .stem-modal__name {
    font-size: 1.6rem;
  }
  
  .stem-modal__body {
    padding: 1.2rem;
  }
  
  .stem-modal__price-value {
    font-size: 1.8rem;
  }
  
  .cart-panel {
    width: 90vw;
    max-width: none;
  }
  
  .cart-item__img {
    width: 45px;
    height: 45px;
  }
  
  .cart-fab {
    width: 50px;
    height: 50px;
    bottom: 1.5rem;
    right: 1.5rem;
    font-size: 1.2rem;
  }
}
</style>