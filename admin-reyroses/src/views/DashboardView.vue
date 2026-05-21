<template>
  <div :class="['flex h-screen font-sans overflow-hidden relative transition-colors duration-300', isDarkTheme ? 'bg-[#0f0f12]' : 'bg-gray-100']">
    <div
      v-if="isMobileMenuOpen"
      @click="isMobileMenuOpen = false"
      :class="['fixed inset-0 z-40 md:hidden backdrop-blur-sm transition-opacity', isDarkTheme ? 'bg-black/70' : 'bg-black/50']"
    ></div>

    <aside
      :class="[
        'w-72 text-white flex flex-col shadow-2xl z-50 absolute inset-y-0 left-0 transform transition-all duration-300 ease-in-out md:relative md:translate-x-0',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
        isDarkTheme ? 'bg-[#16161a] border-r border-[#2a2a32]' : 'bg-white border-r border-gray-200'
      ]"
    >
      <div :class="['p-6 relative', isDarkTheme ? 'border-b border-[#2a2a32]' : 'border-b border-gray-200']">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <h2 :class="['text-lg font-bold tracking-wide', isDarkTheme ? 'text-white' : 'text-gray-900']">Rey Ruses</h2>
            <p :class="['text-xs', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Panel Admin</p>
          </div>
        </div>
        <button
          @click="isMobileMenuOpen = false"
          :class="['md:hidden absolute top-4 right-4 p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-500 hover:text-gray-900']"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <p :class="['text-[10px] font-semibold uppercase tracking-widest mb-4 px-3', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">
          Módulos
        </p>

        <button
          @click="switchModule('catalogo')"
          :class="[
            'w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group',
            currentModule === 'catalogo'
              ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
              : isDarkTheme
                ? 'text-zinc-400 hover:bg-[#1e1e24] hover:text-white'
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
          ]"
        >
          <div :class="['p-2 rounded-lg', currentModule === 'catalogo' ? 'bg-emerald-500/20' : isDarkTheme ? 'bg-[#1e1e24] group-hover:bg-[#2a2a32]' : 'bg-gray-100 group-hover:bg-gray-200']">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
          </div>
          <span class="text-sm font-medium">Catálogo</span>
          <span v-if="currentModule === 'catalogo'" class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        </button>

        <button
          @click="switchModule('pedidos')"
          :class="[
            'w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group',
            currentModule === 'pedidos'
              ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
              : isDarkTheme
                ? 'text-zinc-400 hover:bg-[#1e1e24] hover:text-white'
                : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
          ]"
        >
          <div :class="['p-2 rounded-lg', currentModule === 'pedidos' ? 'bg-emerald-500/20' : isDarkTheme ? 'bg-[#1e1e24] group-hover:bg-[#2a2a32]' : 'bg-gray-100 group-hover:bg-gray-200']">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <span class="text-sm font-medium">Pedidos</span>
          <span v-if="currentModule === 'pedidos'" class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
        </button>

        <button
          :class="[
            'w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all group',
            isDarkTheme
              ? 'text-zinc-400 hover:bg-[#1e1e24] hover:text-white'
              : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900',
          ]"
        >
          <div :class="['p-2 rounded-lg', isDarkTheme ? 'bg-[#1e1e24] group-hover:bg-[#2a2a32]' : 'bg-gray-100 group-hover:bg-gray-200']">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <span class="text-sm font-medium">Clientes</span>
        </button>
      </nav>

      <div :class="['p-4', isDarkTheme ? 'border-t border-[#2a2a32]' : 'border-t border-gray-200']">
        <div :class="['flex items-center gap-3 p-3 rounded-xl mb-3', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-sm font-bold text-white">
            AD
          </div>
          <div class="flex-1 min-w-0">
            <p :class="['text-sm font-semibold truncate', isDarkTheme ? 'text-white' : 'text-gray-900']">Administrador</p>
            <p :class="['text-xs truncate', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">admin@reyroses.com</p>
          </div>
        </div>

        <button
          @click="enableNotifications"
          class="w-full py-2.5 mb-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all flex items-center justify-center gap-2 shadow-md shadow-blue-500/20"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          Activar Alertas
        </button>

        <button
          @click="handleLogout"
          class="w-full py-2.5 text-xs font-medium text-zinc-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          Cerrar sesión
        </button>
      </div>
    </aside>

    <main
      v-if="currentModule === 'catalogo'"
      class="flex-1 flex flex-col h-screen overflow-hidden relative"
    >
      <header :class="['px-4 md:px-8 py-5 flex flex-col md:flex-row justify-between items-start md:items-center z-10 gap-4', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
        <div class="flex items-center w-full md:w-auto">
          <button
            @click="isMobileMenuOpen = true"
            :class="['md:hidden mr-4 p-2 rounded-lg transition-colors', isDarkTheme ? 'text-zinc-400 bg-[#1e1e24] hover:bg-[#2a2a32] hover:text-white' : 'text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-900']"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div>
            <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Inventario</h1>
            <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
              {{ filteredProducts.length }} productos encontrados
            </p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
          <div class="relative flex-grow md:flex-grow-0">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar productos..."
              :class="['w-full pl-10 pr-4 py-2.5 rounded-xl text-sm placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 md:w-64 transition-all', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white' : 'bg-gray-100 border border-gray-200 text-gray-900']"
            />
            <svg :class="['absolute left-3 top-2.5 w-5 h-5', isDarkTheme ? 'text-zinc-500' : 'text-gray-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>

          <button
            @click="toggleSound"
            :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
            :title="isSoundEnabled ? 'Silenciar alertas' : 'Activar sonido'"
          >
            <svg v-if="isSoundEnabled" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 10v4a2 2 0 002 2h3l5 5V3l-5 5H7a2 2 0 00-2 2z" />
            </svg>
            <svg v-else class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h2.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
            </svg>
          </button>

          <button
            @click="toggleTheme"
            :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
            :title="isDarkTheme ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro'"
          >
            <svg v-if="isDarkTheme" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>

          <button
            @click="fetchProducts"
            :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </button>

          <button
            @click="openCreateModal"
            class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo
          </button>
        </div>
      </header>

      <div :class="['px-4 md:px-8 pt-4 flex gap-1 overflow-x-auto', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
        <button
          v-for="tab in ['Todas', 'Rosas', 'Garden', 'Tinted']"
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg',
            activeTab === tab
              ? isDarkTheme ? 'text-emerald-400 bg-[#0f0f12]' : 'text-emerald-600 bg-gray-100'
              : isDarkTheme ? 'text-zinc-500 hover:text-zinc-300 hover:bg-[#1e1e24]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
          ]"
        >
          {{ tab }}
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div v-if="filteredProducts.length === 0" class="text-center py-20">
          <div :class="['w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
            <svg :class="['w-8 h-8', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
          </div>
          <p :class="['text-base', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">No se encontraron productos</p>
          <p :class="['text-sm mt-1', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">Intenta con otra búsqueda o categoría</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          <div
            v-for="product in filteredProducts"
            :key="product.id"
            :class="['rounded-2xl border transition-all group overflow-hidden', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32] hover:border-[#3a3a42]' : 'bg-white border-gray-200 hover:border-gray-300 hover:shadow-lg']"
          >
            <div :class="['relative h-40 overflow-hidden', isDarkTheme ? 'bg-[#1e1e24]' : 'bg-gray-100']">
              <img
                v-if="product.image_path"
                :src="apiUrl + '/storage/' + product.image_path"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg :class="['w-12 h-12', isDarkTheme ? 'text-zinc-700' : 'text-gray-300']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <span
                :class="[
                  'absolute top-3 right-3 text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-sm',
                  product.stock > 5
                    ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                    : product.stock > 0
                      ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30'
                      : 'bg-red-500/20 text-red-400 border border-red-500/30',
                ]"
              >
                Stock: {{ product.stock }}
              </span>
            </div>

            <div class="p-4">
              <div class="flex items-start justify-between gap-2 mb-2">
                <div>
                  <h3 :class="['font-semibold text-base leading-tight line-clamp-1', isDarkTheme ? 'text-white' : 'text-gray-900']">
                    {{ product.name }}
                  </h3>
                  <p :class="['text-[11px] font-medium uppercase tracking-wider mt-1', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                    {{ product.category ? product.category.name : 'Sin categoría' }}
                  </p>
                </div>
              </div>

              <p :class="['text-xs line-clamp-2 mb-4 min-h-[32px]', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                {{ product.description || 'Sin descripción detallada.' }}
              </p>

              <div :class="['flex items-end justify-between pt-3 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-100']">
                <div>
                  <span :class="['text-[10px] font-medium uppercase', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">Desde</span>
                  <p class="text-xl font-bold text-emerald-500">S/ {{ product.price_50 || '0.00' }}</p>
                </div>
                <div class="flex gap-2">
                  <button
                    @click="editProduct(product)"
                    :class="['p-2 rounded-lg transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
                    title="Editar"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button
                    @click="openDeleteConfirmModal(product)"
                    class="p-2 bg-red-500/10 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition-all"
                    title="Eliminar"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <main
      v-if="currentModule === 'pedidos'"
      class="flex-1 flex flex-col h-screen overflow-hidden relative"
    >
      <header :class="['px-4 md:px-8 py-5 flex items-center justify-between z-10', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
        <div class="flex items-center">
          <button
            @click="isMobileMenuOpen = true"
            :class="['md:hidden mr-4 p-2 rounded-lg transition-colors', isDarkTheme ? 'text-zinc-400 bg-[#1e1e24] hover:bg-[#2a2a32] hover:text-white' : 'text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-900']"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div>
            <h1 :class="['text-xl md:text-2xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">Pedidos</h1>
            <p :class="['text-sm mt-0.5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
              {{ filteredOrders.length }} en vista actual
            </p>
          </div>
        </div>
        <div class="flex items-center gap-3">

          <button
            @click="toggleSound"
            :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
            :title="isSoundEnabled ? 'Silenciar alertas' : 'Activar sonido'"
          >
            <svg v-if="isSoundEnabled" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 10v4a2 2 0 002 2h3l5 5V3l-5 5H7a2 2 0 00-2 2z" />
            </svg>
            <svg v-else class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h2.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
            </svg>
          </button>

          <button
            @click="toggleTheme"
            :class="['p-2.5 rounded-xl transition-all', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
            :title="isDarkTheme ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro'"
          >
            <svg v-if="isDarkTheme" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
          </button>

          <button
            @click="fetchOrders"
            :class="['p-2.5 rounded-xl transition-all flex items-center gap-2', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white border border-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 border border-gray-200']"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span class="hidden md:inline font-medium text-sm">Actualizar</span>
          </button>
        </div>
      </header>

      <div :class="['px-4 md:px-8 pt-4 flex gap-1 overflow-x-auto', isDarkTheme ? 'bg-[#16161a] border-b border-[#2a2a32]' : 'bg-white border-b border-gray-200']">
        <button
          v-for="statusTab in ['Todos', 'Pendiente', 'Pagado', 'Cancelado']"
          :key="statusTab"
          @click="activeOrderTab = statusTab"
          :class="[
            'px-5 py-3 text-sm font-medium tracking-wide transition-all relative rounded-t-lg',
            activeOrderTab === statusTab
              ? isDarkTheme ? 'text-emerald-400 bg-[#0f0f12]' : 'text-emerald-600 bg-gray-100'
              : isDarkTheme ? 'text-zinc-500 hover:text-zinc-300 hover:bg-[#1e1e24]' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50',
          ]"
        >
          {{ statusTab }}
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div :class="['rounded-2xl border overflow-hidden', isDarkTheme ? 'bg-[#16161a] border-[#2a2a32]' : 'bg-white border-gray-200']">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead>
                <tr :class="['text-xs uppercase tracking-wider', isDarkTheme ? 'bg-[#1e1e24] text-zinc-500 border-b border-[#2a2a32]' : 'bg-gray-50 text-gray-500 border-b border-gray-200']">
                  <th class="p-4 md:p-5 font-semibold">N Pedido</th>
                  <th class="p-4 md:p-5 font-semibold">Cliente</th>
                  <th class="p-4 md:p-5 font-semibold">Celular</th>
                  <th class="p-4 md:p-5 font-semibold">Fecha</th>
                  <th class="p-4 md:p-5 font-semibold text-center">Total</th>
                  <th class="p-4 md:p-5 font-semibold text-center">Estado</th>
                  <th class="p-4 md:p-5 font-semibold text-center">Accion</th>
                </tr>
              </thead>
              <tbody :class="['text-sm', isDarkTheme ? 'text-zinc-300' : 'text-gray-700']">
                <tr
                  v-for="order in filteredOrders"
                  :key="order.id"
                  :class="['transition-colors', isDarkTheme ? 'border-b border-[#2a2a32] hover:bg-[#1e1e24]' : 'border-b border-gray-100 hover:bg-gray-50']"
                >
                  <td :class="['p-4 md:p-5 font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
                    #{{ order.id.toString().padStart(4, '0') }}
                  </td>
                  <td class="p-4 md:p-5 font-medium">{{ order.customer_name }}</td>
                  <td :class="['p-4 md:p-5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">{{ order.customer_phone }}</td>
                  <td :class="['p-4 md:p-5', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                    {{ new Date(order.created_at).toLocaleDateString() }}
                  </td>
                  <td class="p-4 md:p-5 text-center text-emerald-500 font-bold">
                    S/ {{ order.total_amount }}
                  </td>
                  <td class="p-4 md:p-5 text-center">
                    <span
                      :class="[
                        'px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wide inline-flex items-center gap-1.5',
                        order.status === 'Pendiente'
                          ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30'
                          : order.status === 'Pagado'
                            ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'
                            : 'bg-red-500/20 text-red-400 border border-red-500/30',
                      ]"
                    >
                      <span class="w-1.5 h-1.5 rounded-full" :class="[
                        order.status === 'Pendiente' ? 'bg-amber-400' :
                        order.status === 'Pagado' ? 'bg-emerald-400' : 'bg-red-400'
                      ]"></span>
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="p-4 md:p-5 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button
                        @click="openOrderDetails(order)"
                        :class="['p-2 rounded-lg transition-colors', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32] hover:text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900']"
                        title="Ver Detalle"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                      </button>
                      <template v-if="order.status === 'Pendiente'">
                        <button
                          @click="openOrderStatusConfirmModal(order.id, 'Pagado')"
                          class="p-2 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-lg transition-colors"
                          title="Marcar como Pagado"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                          </svg>
                        </button>
                        <button
                          @click="openOrderStatusConfirmModal(order.id, 'Cancelado')"
                          class="p-2 bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white rounded-lg transition-colors"
                          title="Cancelar Pedido"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </template>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredOrders.length === 0">
                  <td colspan="7" :class="['p-10 text-center font-medium', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
                    No hay pedidos con el estado "{{ activeOrderTab }}".
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>

    <div
      v-if="showModal"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']"
    >
      <div :class="['rounded-2xl p-6 md:p-8 w-full max-w-md shadow-2xl max-h-[90vh] overflow-y-auto', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div :class="['flex items-center justify-between mb-6 pb-4 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
            {{ isEditing ? 'Editar Variedad' : 'Agregar Producto' }}
          </h2>
          <button @click="closeModal" :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="saveProduct" class="space-y-5">
          <div>
            <label :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Categoria</label>
            <select
              v-model="newProduct.category_id"
              required
              :class="['w-full px-4 py-3 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none cursor-pointer', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white' : 'bg-gray-100 border border-gray-200 text-gray-900']"
            >
              <option value="" disabled :class="isDarkTheme ? 'bg-[#1e1e24]' : 'bg-white'">Selecciona una categoria...</option>
              <option value="1" :class="isDarkTheme ? 'bg-[#1e1e24]' : 'bg-white'">Rosas</option>
              <option value="2" :class="isDarkTheme ? 'bg-[#1e1e24]' : 'bg-white'">Garden</option>
              <option value="3" :class="isDarkTheme ? 'bg-[#1e1e24]' : 'bg-white'">Tinted</option>
            </select>
          </div>

          <div>
            <label :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Nombre de la variedad</label>
            <input
              v-model="newProduct.name"
              type="text"
              placeholder="Ej: Rosa Explorer"
              required
              :class="['w-full px-4 py-3 rounded-xl font-medium focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-white placeholder-zinc-600' : 'bg-gray-100 border border-gray-200 text-gray-900 placeholder-gray-400']"
            />
          </div>

          <div>
            <label :class="['block text-xs font-semibold uppercase tracking-wide mb-2', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
              Fotografia {{ isEditing ? '- Opcional' : '' }}
            </label>
            <input
              type="file"
              @change="handleFileUpload"
              accept="image/webp, image/png, image/jpeg"
              :class="['w-full px-4 py-2 rounded-xl focus:ring-2 focus:ring-emerald-500/50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-500/20 file:text-emerald-400 hover:file:bg-emerald-500/30 cursor-pointer', isDarkTheme ? 'bg-[#1e1e24] border border-[#2a2a32] text-zinc-400' : 'bg-gray-100 border border-gray-200 text-gray-500']"
            />
          </div>

          <div :class="['p-4 rounded-xl border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
            <label :class="['block text-xs font-semibold uppercase tracking-wide mb-3', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Precio y Stock por Tallo</label>
            <div class="grid grid-cols-5 gap-3">
              <div v-for="size in ['50', '60', '70', '80', '90']" :key="size" class="text-center flex flex-col gap-2">
                <span :class="['text-[10px] font-bold', isDarkTheme ? 'text-zinc-600' : 'text-gray-400']">{{ size }}cm</span>

                <input
                  v-model="newProduct['price_' + size]"
                  type="number"
                  step="0.01"
                  placeholder="S/ Precio"
                  :class="['w-full px-1 py-2 text-xs font-bold rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none text-center', isDarkTheme ? 'text-white bg-[#16161a] border border-[#2a2a32]' : 'text-gray-900 bg-white border border-gray-200']"
                />

                <input
                  v-model="newProduct['stock_' + size]"
                  type="number"
                  placeholder="Stock"
                  :class="['w-full px-1 py-2 text-xs font-bold rounded-lg focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 outline-none text-center', isDarkTheme ? 'text-emerald-400 bg-[#16161a] border border-emerald-900/30' : 'text-emerald-600 bg-white border border-emerald-200']"
                />
              </div>
            </div>
          </div>

          <div :class="['flex justify-end gap-3 mt-8 pt-4 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
            <button
              type="button"
              @click="closeModal"
              :class="['px-5 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'text-zinc-400 hover:bg-[#1e1e24]' : 'text-gray-500 hover:bg-gray-100']"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-5 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="showOrderModal"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']"
    >
      <div :class="['rounded-2xl p-6 md:p-8 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div :class="['flex justify-between items-center pb-4 mb-4 shrink-0 border-b', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <h2 :class="['text-lg md:text-xl font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">
            Pedido #{{ selectedOrder.id.toString().padStart(4, '0') }}
          </h2>
          <button @click="showOrderModal = false" :class="['p-1', isDarkTheme ? 'text-zinc-500 hover:text-white' : 'text-gray-400 hover:text-gray-900']">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div :class="['mb-6 p-4 rounded-xl text-sm shrink-0 border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']">
          <p class="mb-2 flex items-center">
            <span :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Cliente:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ selectedOrder.customer_name }}</span>
          </p>
          <p class="mb-2 flex items-center">
            <span :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Celular:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ selectedOrder.customer_phone }}</span>
          </p>
          <p class="flex items-center">
            <span :class="['font-semibold w-20 uppercase text-[10px] tracking-wider', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Direccion:</span>
            <span :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ selectedOrder.customer_address }}</span>
          </p>
        </div>

        <h3 :class="['text-xs font-semibold uppercase tracking-widest mb-3 shrink-0', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          Rosas Solicitadas
        </h3>
        <ul class="space-y-3 mb-6 overflow-y-auto pr-2 flex-grow">
          <li
            v-for="item in selectedOrder.items"
            :key="item.id"
            :class="['flex flex-col sm:flex-row justify-between sm:items-center p-3 rounded-xl gap-2 border', isDarkTheme ? 'bg-[#1e1e24] border-[#2a2a32]' : 'bg-gray-50 border-gray-200']"
          >
            <div>
              <p :class="['font-semibold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ item.quantity }}x {{ item.product_name }}</p>
              <p :class="['text-xs', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">Precio unitario: S/ {{ item.price }}</p>
            </div>
            <p class="font-bold text-emerald-500">S/ {{ (item.price * item.quantity).toFixed(2) }}</p>
          </li>
        </ul>

        <div :class="['flex justify-between items-center pt-4 shrink-0 border-t', isDarkTheme ? 'border-[#2a2a32]' : 'border-gray-200']">
          <span :class="['font-semibold', isDarkTheme ? 'text-zinc-400' : 'text-gray-500']">Total a Cobrar:</span>
          <span class="text-xl md:text-2xl font-bold text-emerald-500">S/ {{ selectedOrder.total_amount }}</span>
        </div>
      </div>
    </div>

    <div
      v-if="showStatusConfirmModal"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']"
    >
      <div :class="['rounded-2xl p-6 md:p-8 w-full max-w-sm shadow-2xl text-center', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div
          :class="[
            'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5',
            pendingStatusUpdate.status === 'Pagado' ? 'bg-emerald-500/20' : 'bg-red-500/20'
          ]"
        >
          <svg v-if="pendingStatusUpdate.status === 'Pagado'" class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <svg v-else class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h2 :class="['text-xl font-bold mb-2', isDarkTheme ? 'text-white' : 'text-gray-900']">Confirmar accion</h2>
        <p :class="['text-sm mb-8 leading-relaxed', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          Vas a marcar este pedido como
          <span :class="['font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">{{ pendingStatusUpdate.status }}</span>.
          Esta accion no se puede deshacer.
        </p>
        <div class="flex gap-3">
          <button
            @click="showStatusConfirmModal = false"
            :class="['flex-1 px-4 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200']"
          >
            Cancelar
          </button>
          <button
            @click="executeOrderStatusUpdate"
            :class="[
              'flex-1 px-4 py-3 font-semibold text-sm rounded-xl text-white shadow-lg transition-all',
              pendingStatusUpdate.status === 'Pagado'
                ? 'bg-emerald-500 hover:bg-emerald-600 shadow-emerald-500/20'
                : 'bg-red-500 hover:bg-red-600 shadow-red-500/20',
            ]"
          >
            Confirmar
          </button>
        </div>
      </div>
    </div>

    <div
      v-if="showDeleteModal"
      :class="['fixed inset-0 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4', isDarkTheme ? 'bg-black/70' : 'bg-black/50']"
    >
      <div :class="['rounded-2xl p-6 md:p-8 w-full max-w-sm shadow-2xl text-center', isDarkTheme ? 'bg-[#16161a] border border-[#2a2a32]' : 'bg-white border border-gray-200']">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-5">
          <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h2 :class="['text-xl font-bold mb-2', isDarkTheme ? 'text-white' : 'text-gray-900']">Eliminar Variedad</h2>
        <p :class="['text-sm mb-8 leading-relaxed', isDarkTheme ? 'text-zinc-500' : 'text-gray-500']">
          Estas seguro de eliminar
          <span :class="['font-bold', isDarkTheme ? 'text-white' : 'text-gray-900']">"{{ productToDelete?.name }}"</span>?
          No podras recuperarla.
        </p>
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            :class="['flex-1 px-4 py-3 font-semibold text-sm rounded-xl transition-colors', isDarkTheme ? 'bg-[#1e1e24] text-zinc-400 hover:bg-[#2a2a32]' : 'bg-gray-100 text-gray-500 hover:bg-gray-200']"
          >
            Cancelar
          </button>
          <button
            @click="executeDeleteProduct"
            class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 font-semibold text-sm rounded-xl text-white shadow-lg shadow-red-500/20 transition-all"
          >
            Si, Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { toast } from 'vue-sonner'
import { useRouter } from 'vue-router'

// NUEVOS IMPORTS DE FIREBASE (Asegúrate de que la ruta coincida con tu archivo firebase.js)
import { requestForToken, onMessageListener } from '@/firebase'

const apiUrl = import.meta.env.VITE_API_URL;

// --- TEMA ---
const isDarkTheme = ref(true)

// --- CONTROL DE SONIDO ---
const isSoundEnabled = ref(true)

const toggleSound = () => {
  isSoundEnabled.value = !isSoundEnabled.value
  localStorage.setItem('soundEnabled', isSoundEnabled.value ? 'true' : 'false')

  if (isSoundEnabled.value) {
    toast.success('Sonido de alertas activado', { duration: 2000 })
  } else {
    toast.info('Alertas silenciadas', { duration: 2000 })
  }
}

const toggleTheme = () => {
  isDarkTheme.value = !isDarkTheme.value
  localStorage.setItem('theme', isDarkTheme.value ? 'dark' : 'light')
}

// --- CONTROL DE MÓDULOS ---
const currentModule = ref('catalogo')
const isMobileMenuOpen = ref(false)

// --- VARIABLES CATÁLOGO ---
const products = ref([])
const showModal = ref(false)
const activeTab = ref('Todas')
const searchQuery = ref('')
const imageFile = ref(null)
const isEditing = ref(false)
const editingId = ref(null)

const newProduct = ref({
  name: '',
  category_id: '',
  price_50: '', stock_50: 0,
  price_60: '', stock_60: 0,
  price_70: '', stock_70: 0,
  price_80: '', stock_80: 0,
  price_90: '', stock_90: 0,
})

// Variables para Modal de Eliminar Producto
const showDeleteModal = ref(false)
const productToDelete = ref(null)

// --- VARIABLES PEDIDOS ---
const orders = ref([])
const activeOrderTab = ref('Todos')
const showOrderModal = ref(false)
const selectedOrder = ref(null)

// Variables para Modal de Confirmar Estado de Pedido
const showStatusConfirmModal = ref(false)
const pendingStatusUpdate = ref({ id: null, status: '' })

// --- LÓGICA DE NOTIFICACIONES PUSH (FIREBASE) ---
const fcmToken = ref('');

const enableNotifications = async () => {
  try {
    const token = await requestForToken();

    if (token) {
      fcmToken.value = token;

      // 👇 Usar la ruta que ya existe en tu api.php
      await axios.post(`${apiUrl}/api/save-fcm-token`,
        { fcm_token: token },
        { headers: getAuthHeaders() }
      );

      toast.success("¡Notificaciones activadas correctamente!");
    } else {
      toast.error("No diste permiso o hubo un error al generar el token.");
    }
  } catch (error) {
    console.error("Error activando notificaciones:", error);
    toast.error("Ocurrió un problema al activar las notificaciones.");
  }
};
// --- AUTENTICACIÓN ---
const getAuthHeaders = () => {
  const token = localStorage.getItem('auth_token')
  return { Authorization: `Bearer ${token}` }
}
//cerrar sesión

const router = useRouter()

const handleLogout = async () => {
  try {
    const token = localStorage.getItem('auth_token')

    if (token) {
      await axios.post(`${import.meta.env.VITE_API_URL}/api/logout`, {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
    }
  } catch (error) {
    console.error('Error al cerrar sesión en el servidor:', error)
  } finally {
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user')
    router.push('/')
  }
}

// --- LÓGICA DE NAVEGACIÓN ---
const switchModule = (module) => {
  currentModule.value = module
  isMobileMenuOpen.value = false
  if (module === 'pedidos') fetchOrders()
  if (module === 'catalogo') fetchProducts()
}

// --- FUNCIONES DEL CATÁLOGO ---
const fetchProducts = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/products`, {
      headers: getAuthHeaders(),
    })
    products.value = response.data
  } catch (error) {
    console.error('Error conectando con la base de datos:', error)
  }
}

const filteredProducts = computed(() => {
  let result = products.value
  if (activeTab.value !== 'Todas') {
    result = result.filter((p) => p.category && p.category.name === activeTab.value)
  }
  if (searchQuery.value.trim() !== '') {
    const query = searchQuery.value.toLowerCase()
    result = result.filter((p) => p.name.toLowerCase().includes(query))
  }
  return result
})

const handleFileUpload = (event) => {
  imageFile.value = event.target.files[0]
}

const openCreateModal = () => {
  isEditing.value = false
  editingId.value = null
  newProduct.value = {
    name: '', category_id: '',
    price_50: '', stock_50: 0,
    price_60: '', stock_60: 0,
    price_70: '', stock_70: 0,
    price_80: '', stock_80: 0,
    price_90: '', stock_90: 0,
  }
  imageFile.value = null
  showModal.value = true
}

const editProduct = (product) => {
  isEditing.value = true
  editingId.value = product.id
  newProduct.value = {
    name: product.name,
    category_id: product.category_id,
    price_50: product.price_50, stock_50: product.stock_50 || 0,
    price_60: product.price_60, stock_60: product.stock_60 || 0,
    price_70: product.price_70, stock_70: product.stock_70 || 0,
    price_80: product.price_80, stock_80: product.stock_80 || 0,
    price_90: product.price_90, stock_90: product.stock_90 || 0,
  }
  imageFile.value = null
  showModal.value = true
}

const saveProduct = async () => {
  try {
    const formData = new FormData()
    formData.append('category_id', newProduct.value.category_id)
    formData.append('name', newProduct.value.name)

    formData.append('price_50', newProduct.value.price_50)
    formData.append('stock_50', newProduct.value.stock_50)

    formData.append('price_60', newProduct.value.price_60)
    formData.append('stock_60', newProduct.value.stock_60)

    formData.append('price_70', newProduct.value.price_70)
    formData.append('stock_70', newProduct.value.stock_70)

    formData.append('price_80', newProduct.value.price_80)
    formData.append('stock_80', newProduct.value.stock_80)

    formData.append('price_90', newProduct.value.price_90)
    formData.append('stock_90', newProduct.value.stock_90)

    if (imageFile.value) formData.append('image', imageFile.value)

    if (isEditing.value) {
      formData.append('_method', 'PUT')
      await axios.post(`${import.meta.env.VITE_API_URL}/api/products/${editingId.value}`, formData, {
        headers: { ...getAuthHeaders(), 'Content-Type': 'multipart/form-data' },
      })
      toast.success('Variedad actualizada correctamente')
    } else {
      await axios.post(`${import.meta.env.VITE_API_URL}/api/products`, formData, {
        headers: { ...getAuthHeaders(), 'Content-Type': 'multipart/form-data' },
      })
      toast.success('Nueva variedad agregada al catálogo')
    }
    closeModal()
    fetchProducts()
  } catch (error) {
    console.error('Error al guardar:', error)
    if (error.response && error.response.data && error.response.data.errors) {
      const mensajesErrores = Object.values(error.response.data.errors).flat().join(' | ')
      toast.error('Revisa los datos: ' + mensajesErrores)
    } else {
      toast.error('Ocurrió un error inesperado al guardar.')
    }
  }
}

const closeModal = () => {
  showModal.value = false
  isEditing.value = false
  editingId.value = null
  newProduct.value = {
    name: '', category_id: '',
    price_50: '', stock_50: 0,
    price_60: '', stock_60: 0,
    price_70: '', stock_70: 0,
    price_80: '', stock_80: 0,
    price_90: '', stock_90: 0,
  }
  imageFile.value = null
}

const openDeleteConfirmModal = (product) => {
  productToDelete.value = product
  showDeleteModal.value = true
}

const executeDeleteProduct = async () => {
  if (!productToDelete.value) return
  try {
    await axios.delete(`${import.meta.env.VITE_API_URL}/api/products/${productToDelete.value.id}`, {
      headers: getAuthHeaders(),
    })
    toast.success(`La variedad "${productToDelete.value.name}" fue eliminada`)
    fetchProducts()
  } catch (error) {
    console.error('Error al eliminar:', error)
    toast.error('No se pudo eliminar el producto')
  } finally {
    showDeleteModal.value = false
    productToDelete.value = null
  }
}

// --- FUNCIONES DE PEDIDOS ---
const fetchOrders = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/orders`, {
      headers: getAuthHeaders(),
    })
    orders.value = response.data
  } catch (error) {
    console.error('Error cargando pedidos:', error)
  }
}

const filteredOrders = computed(() => {
  if (activeOrderTab.value === 'Todos') return orders.value
  return orders.value.filter((o) => o.status === activeOrderTab.value)
})

const openOrderDetails = (order) => {
  selectedOrder.value = order
  showOrderModal.value = true
}

const openOrderStatusConfirmModal = (id, newStatus) => {
  pendingStatusUpdate.value = { id, status: newStatus }
  showStatusConfirmModal.value = true
}

const executeOrderStatusUpdate = async () => {
  const { id, status } = pendingStatusUpdate.value
  try {
    await axios.put(
      `${import.meta.env.VITE_API_URL}/api/orders/${id}/status`,
      { status },
      { headers: getAuthHeaders() },
    )
    toast.success(`Pedido #${id.toString().padStart(4, '0')} marcado como: ${status}`)
    fetchOrders()
  } catch (error) {
    console.error('Error al actualizar el estado:', error)
    toast.error('Hubo un error al procesar la solicitud.')
  } finally {
    showStatusConfirmModal.value = false
  }
}

onMounted(() => {
  // 1. Cargar tema guardado
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    isDarkTheme.value = savedTheme === 'dark'
  }

  // Cargar preferencia de sonido guardada en caché
  const savedSound = localStorage.getItem('soundEnabled')
  if (savedSound !== null) {
    isSoundEnabled.value = savedSound === 'true'
  }

  // 2. Cargar los productos para que vuelva a aparecer tu inventario
  fetchProducts()

  // 3. Iniciar la escucha continua de Firebase (SIN .then)
  try {
    onMessageListener((payload) => {
      console.log("¡Pedido recibido en tiempo real!", payload);

      // Intentar reproducir el sonido SOLO si no está muteado
      if (isSoundEnabled.value) {
        try {
          const audio = new Audio('/alert.mp3');
          audio.play();
        } catch (audioError) {
          console.warn("El navegador bloqueó el sonido hasta que interactúes con la página:", audioError);
        }
      }

      // Mostrar la alerta visual
      toast.success(`🔔 NUEVO PEDIDO: ${payload.notification?.title || 'Ingreso'} - ${payload.notification?.body || ''}`, {
        duration: 10000
      });

      // Recargar automáticamente la lista si estás en pedidos
      if (currentModule.value === 'pedidos') {
        fetchOrders();
      }
    });
  } catch (err) {
    console.error("Error al configurar el escuchador de Firebase:", err);
  }
})
</script>
