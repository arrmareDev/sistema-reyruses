<template>
  <div class="flex h-screen bg-[#F8F8F8] font-sans overflow-hidden relative">
    <div
      v-if="isMobileMenuOpen"
      @click="isMobileMenuOpen = false"
      class="fixed inset-0 bg-neutral-900/60 z-40 md:hidden backdrop-blur-sm transition-opacity"
    ></div>

    <aside
      :class="[
        'w-64 bg-neutral-900 text-white flex flex-col shadow-2xl z-50 absolute inset-y-0 left-0 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <div
        class="p-6 border-b border-neutral-800 flex flex-col items-center justify-center relative"
      >
        <h2 class="text-xl font-bold tracking-widest uppercase text-white mt-2">Rey Roses</h2>
        <p class="text-xs text-neutral-400 tracking-widest mt-1">Panel Admin</p>
        <button
          @click="isMobileMenuOpen = false"
          class="md:hidden absolute top-4 right-4 text-neutral-400 hover:text-white"
        >
          ✕
        </button>
      </div>

      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest mb-3 px-2 mt-4">
          Módulos
        </p>

        <button
          @click="switchModule('catalogo')"
          :class="[
            'w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all shadow-sm',
            currentModule === 'catalogo'
              ? 'bg-[#E8751A] text-white'
              : 'text-neutral-400 hover:bg-neutral-800 hover:text-white',
          ]"
        >
          <span class="text-lg">🌸</span>
          <span class="text-sm font-semibold tracking-wide">Catálogo</span>
        </button>

        <button
          @click="switchModule('pedidos')"
          :class="[
            'w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition-all shadow-sm',
            currentModule === 'pedidos'
              ? 'bg-[#E8751A] text-white'
              : 'text-neutral-400 hover:bg-neutral-800 hover:text-white',
          ]"
        >
          <span class="text-lg">📦</span>
          <span class="text-sm font-semibold tracking-wide">Pedidos</span>
        </button>

        <button
          class="w-full flex items-center space-x-3 px-4 py-3 text-neutral-400 hover:bg-neutral-800 hover:text-white rounded-xl transition-all"
        >
          <span class="text-lg">👥</span>
          <span class="text-sm font-medium tracking-wide">Clientes</span>
        </button>
      </nav>

      <div class="p-4 border-t border-neutral-800">
        <div class="flex items-center space-x-3 mb-4 px-2">
          <div
            class="w-10 h-10 rounded-full bg-neutral-700 flex items-center justify-center text-sm font-bold"
          >
            AD
          </div>
          <div>
            <p class="text-sm font-bold">Administrador</p>
            <p class="text-xs text-neutral-400">admin@reyroses.com</p>
          </div>
        </div>
        <button
          class="w-full py-2.5 text-xs font-bold text-neutral-400 hover:text-white hover:bg-neutral-800 rounded-lg transition-all flex items-center justify-center space-x-2"
        >
          <span>🚪 Cerrar sesión</span>
        </button>
      </div>
    </aside>

    <main
      v-if="currentModule === 'catalogo'"
      class="flex-1 flex flex-col h-screen overflow-hidden relative"
    >
      <header
        class="bg-white px-4 md:px-8 py-4 md:py-6 border-b border-neutral-200 flex flex-col md:flex-row justify-between items-start md:items-center z-10 gap-4"
      >
        <div class="flex items-center w-full md:w-auto">
          <button
            @click="isMobileMenuOpen = true"
            class="md:hidden mr-4 p-2 text-neutral-600 bg-neutral-100 rounded-lg hover:bg-neutral-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              ></path>
            </svg>
          </button>
          <div>
            <h1 class="text-xl md:text-2xl font-bold text-neutral-800 tracking-wide">Inventario</h1>
            <p class="text-xs md:text-sm text-neutral-500 mt-1">
              {{ filteredProducts.length }} productos encontrados
            </p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
          <div class="relative flex-grow md:flex-grow-0">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar..."
              class="w-full pl-10 pr-4 py-2.5 bg-neutral-100 border-none rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#E8751A] md:w-64 transition-all"
            />
            <span class="absolute left-3 top-2.5 text-neutral-400">🔍</span>
          </div>
          <button
            @click="fetchProducts"
            class="p-2.5 bg-neutral-100 text-neutral-600 rounded-xl hover:bg-neutral-200 transition-all"
          >
            🔄
          </button>
          <button
            @click="openCreateModal"
            class="bg-[#E8751A] text-white px-4 py-2.5 rounded-xl font-bold text-sm tracking-wide hover:bg-[#d66a15] shadow-md transition-all whitespace-nowrap"
          >
            + Nuevo
          </button>
        </div>
      </header>

      <div
        class="px-4 md:px-8 pt-4 bg-white border-b border-neutral-200 flex space-x-6 md:space-x-8 overflow-x-auto whitespace-nowrap"
      >
        <button
          v-for="tab in ['Todas', 'Rosas', 'Garden', 'Tinted']"
          :key="tab"
          @click="activeTab = tab"
          :class="[
            'pb-4 text-sm font-bold tracking-wide transition-all relative shrink-0',
            activeTab === tab ? 'text-[#E8751A]' : 'text-neutral-400 hover:text-neutral-600',
          ]"
        >
          {{ tab }}
          <div
            v-if="activeTab === tab"
            class="absolute bottom-0 left-0 w-full h-1 bg-[#E8751A] rounded-t-md"
          ></div>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div v-if="filteredProducts.length === 0" class="text-center py-20">
          <p class="text-neutral-400 text-lg">
            No se encontraron variedades que coincidan con la búsqueda.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
          <div
            v-for="product in filteredProducts"
            :key="product.id"
            class="bg-white rounded-2xl p-5 border border-neutral-200 hover:shadow-xl transition-all flex flex-col group"
          >
            <div class="flex justify-between items-start mb-4">
              <div class="flex items-center space-x-3">
                <div
                  class="w-12 h-12 rounded-lg bg-neutral-100 overflow-hidden border border-neutral-200 shrink-0"
                >
                  <img
                    v-if="product.image_path"
                    :src="apiUrl + '/storage/' + product.image_path"
                    class="w-full h-full object-cover"
                  />
                  <span
                    v-else
                    class="flex items-center justify-center w-full h-full text-xs text-neutral-400"
                    >Sin foto</span
                  >
                </div>
                <div>
                  <h3 class="font-bold text-neutral-800 text-lg leading-tight">
                    {{ product.name }}
                  </h3>
                  <p class="text-[11px] font-bold uppercase tracking-wider text-neutral-400">
                    {{ product.category ? product.category.name : 'Sin categoría' }}
                  </p>
                </div>
              </div>
              <span
                :class="[
                  'text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0',
                  product.stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700',
                ]"
              >
                Stock: {{ product.stock }}
              </span>
            </div>
            <div class="w-full h-[1px] bg-neutral-100 my-2"></div>
            <div class="py-2 flex-grow">
              <p class="text-xs text-neutral-500 line-clamp-2">
                {{ product.description || 'Sin descripción detallada.' }}
              </p>
              <div class="mt-4 flex items-end space-x-1">
                <span class="text-xs font-bold text-neutral-400 mb-1">Desde:</span>
                <span class="text-xl font-black text-[#E8751A]"
                  >S/ {{ product.price_50 || '0.00' }}</span
                >
              </div>
            </div>
            <div class="flex space-x-2 mt-4 pt-4 border-t border-neutral-100">
              <button
                @click="editProduct(product)"
                class="flex-1 bg-neutral-100 text-neutral-600 py-2 rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-neutral-200 transition-all"
              >
                Editar
              </button>
              <button
                @click="openDeleteConfirmModal(product)"
                class="px-4 bg-red-50 text-red-500 py-2 rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-red-500 hover:text-white transition-all"
              >
                Borrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <main
      v-if="currentModule === 'pedidos'"
      class="flex-1 flex flex-col h-screen overflow-hidden relative bg-[#F8F8F8]"
    >
      <header
        class="bg-white px-4 md:px-8 py-4 md:py-6 border-b border-neutral-200 flex items-center justify-between z-10"
      >
        <div class="flex items-center">
          <button
            @click="isMobileMenuOpen = true"
            class="md:hidden mr-4 p-2 text-neutral-600 bg-neutral-100 rounded-lg hover:bg-neutral-200"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              ></path>
            </svg>
          </button>
          <div>
            <h1 class="text-xl md:text-2xl font-bold text-neutral-800 tracking-wide">Pedidos</h1>
            <p class="text-xs md:text-sm text-neutral-500 mt-1">
              {{ filteredOrders.length }} en vista actual
            </p>
          </div>
        </div>
        <button
          @click="fetchOrders"
          class="p-2.5 bg-neutral-100 text-neutral-600 rounded-xl hover:bg-neutral-200 transition-all flex items-center gap-2"
        >
          <span class="hidden md:inline font-medium text-sm">🔄 Actualizar Lista</span>
          <span class="md:hidden">🔄</span>
        </button>
      </header>

      <div
        class="px-4 md:px-8 pt-4 bg-white border-b border-neutral-200 flex space-x-6 md:space-x-8 overflow-x-auto whitespace-nowrap"
      >
        <button
          v-for="statusTab in ['Todos', 'Pendiente', 'Pagado', 'Cancelado']"
          :key="statusTab"
          @click="activeOrderTab = statusTab"
          :class="[
            'pb-4 text-sm font-bold tracking-wide transition-all relative shrink-0',
            activeOrderTab === statusTab
              ? 'text-[#E8751A]'
              : 'text-neutral-400 hover:text-neutral-600',
          ]"
        >
          {{ statusTab }}
          <div
            v-if="activeOrderTab === statusTab"
            class="absolute bottom-0 left-0 w-full h-1 bg-[#E8751A] rounded-t-md"
          ></div>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
              <thead>
                <tr
                  class="bg-neutral-50 text-neutral-500 text-xs uppercase tracking-wider border-b border-neutral-200"
                >
                  <th class="p-4 md:p-5 font-bold">N° Pedido</th>
                  <th class="p-4 md:p-5 font-bold">Cliente</th>
                  <th class="p-4 md:p-5 font-bold">Celular</th>
                  <th class="p-4 md:p-5 font-bold">Fecha</th>
                  <th class="p-4 md:p-5 font-bold text-center">Total</th>
                  <th class="p-4 md:p-5 font-bold text-center">Estado</th>
                  <th class="p-4 md:p-5 font-bold text-center">Acción</th>
                </tr>
              </thead>
              <tbody class="text-sm text-neutral-700">
                <tr
                  v-for="order in filteredOrders"
                  :key="order.id"
                  class="border-b border-neutral-100 hover:bg-neutral-50 transition-colors"
                >
                  <td class="p-4 md:p-5 font-bold text-neutral-900">
                    #{{ order.id.toString().padStart(4, '0') }}
                  </td>
                  <td class="p-4 md:p-5 font-semibold">{{ order.customer_name }}</td>
                  <td class="p-4 md:p-5">{{ order.customer_phone }}</td>
                  <td class="p-4 md:p-5 text-neutral-500">
                    {{ new Date(order.created_at).toLocaleDateString() }}
                  </td>
                  <td class="p-4 md:p-5 text-center text-[#E8751A] font-bold">
                    S/ {{ order.total_amount }}
                  </td>
                  <td class="p-4 md:p-5 text-center">
                    <span
                      :class="[
                        'px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter inline-flex items-center gap-1.5',
                        order.status === 'Pendiente'
                          ? 'bg-yellow-100 text-yellow-700'
                          : order.status === 'Pagado'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700',
                      ]"
                    >
                      <svg
                        v-if="order.status === 'Pendiente'"
                        class="w-3 h-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                      </svg>
                      {{ order.status }}
                    </span>
                  </td>
                  <td class="p-4 md:p-5 text-center">
                    <div class="flex items-center justify-center space-x-2">
                      <button
                        @click="openOrderDetails(order)"
                        class="p-2 bg-neutral-100 text-neutral-600 hover:bg-neutral-200 rounded-lg transition-colors"
                        title="Ver Detalle"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                          ></path>
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                          ></path>
                        </svg>
                      </button>
                      <template v-if="order.status === 'Pendiente'">
                        <button
                          @click="openOrderStatusConfirmModal(order.id, 'Pagado')"
                          class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-colors"
                          title="Marcar como Pagado"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M5 13l4 4L19 7"
                            ></path>
                          </svg>
                        </button>
                        <button
                          @click="openOrderStatusConfirmModal(order.id, 'Cancelado')"
                          class="p-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-lg transition-colors"
                          title="Cancelar Pedido"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"
                            ></path>
                          </svg>
                        </button>
                      </template>
                    </div>
                  </td>
                </tr>
                <tr v-if="filteredOrders.length === 0">
                  <td colspan="7" class="p-10 text-center text-neutral-400 font-medium">
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
      class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4"
    >
      <div
        class="bg-white rounded-2xl p-6 md:p-8 w-full max-w-md shadow-2xl transform transition-all max-h-[90vh] overflow-y-auto"
      >
        <h2
          class="text-xl font-bold text-neutral-800 mb-6 uppercase tracking-wide text-center border-b pb-4"
        >
          {{ isEditing ? 'Editar Variedad' : 'Agregar Producto' }}
        </h2>
        <form @submit.prevent="saveProduct" class="space-y-4 md:space-y-5">
          <div>
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
              >Categoría</label
            >
            <select
              v-model="newProduct.category_id"
              required
              class="w-full px-4 py-3 bg-white border border-neutral-300 rounded-lg text-black font-medium focus:ring-2 focus:ring-[#E8751A] outline-none cursor-pointer"
            >
              <option value="" disabled>Selecciona una categoría...</option>
              <option value="1">Rosas</option>
              <option value="2">Garden</option>
              <option value="3">Tinted</option>
            </select>
          </div>
          <div>
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
              >Nombre de la variedad</label
            >
            <input
              v-model="newProduct.name"
              type="text"
              placeholder="Ej: Rosa Explorer"
              required
              class="w-full px-4 py-3 bg-white border border-neutral-300 rounded-lg text-black font-medium focus:ring-2 focus:ring-[#E8751A] outline-none"
            />
          </div>
          <div>
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
              >Fotografía {{ isEditing ? '- Opcional' : '' }}</label
            >
            <input
              type="file"
              @change="handleFileUpload"
              accept="image/webp, image/png, image/jpeg"
              class="w-full px-4 py-2 bg-white border border-neutral-300 rounded-lg text-neutral-600 focus:ring-2 focus:ring-[#E8751A] file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-neutral-100 file:text-neutral-700 hover:file:bg-neutral-200 cursor-pointer"
            />
          </div>
          <div class="bg-neutral-50 p-4 rounded-xl border border-neutral-200">
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-3"
              >Precios por Tallo (S/)</label
            >
            <div class="grid grid-cols-5 gap-2">
              <div>
                <span class="text-[10px] font-bold text-neutral-400">50cm</span
                ><input
                  v-model="newProduct.price_50"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-1 py-2 text-sm text-black font-bold bg-white border border-neutral-300 rounded-md focus:ring-2 focus:ring-[#E8751A] outline-none text-center"
                />
              </div>
              <div>
                <span class="text-[10px] font-bold text-neutral-400">60cm</span
                ><input
                  v-model="newProduct.price_60"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-1 py-2 text-sm text-black font-bold bg-white border border-neutral-300 rounded-md focus:ring-2 focus:ring-[#E8751A] outline-none text-center"
                />
              </div>
              <div>
                <span class="text-[10px] font-bold text-neutral-400">70cm</span
                ><input
                  v-model="newProduct.price_70"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-1 py-2 text-sm text-black font-bold bg-white border border-neutral-300 rounded-md focus:ring-2 focus:ring-[#E8751A] outline-none text-center"
                />
              </div>
              <div>
                <span class="text-[10px] font-bold text-neutral-400">80cm</span
                ><input
                  v-model="newProduct.price_80"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-1 py-2 text-sm text-black font-bold bg-white border border-neutral-300 rounded-md focus:ring-2 focus:ring-[#E8751A] outline-none text-center"
                />
              </div>
              <div>
                <span class="text-[10px] font-bold text-neutral-400">90cm</span
                ><input
                  v-model="newProduct.price_90"
                  type="number"
                  step="0.01"
                  required
                  class="w-full px-1 py-2 text-sm text-black font-bold bg-white border border-neutral-300 rounded-md focus:ring-2 focus:ring-[#E8751A] outline-none text-center"
                />
              </div>
            </div>
          </div>
          <div>
            <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wide mb-2"
              >Stock Disponible</label
            >
            <input
              v-model="newProduct.stock"
              type="number"
              placeholder="Cantidad"
              required
              class="w-full px-4 py-3 bg-white border border-neutral-300 rounded-lg text-black font-medium focus:ring-2 focus:ring-[#E8751A] outline-none"
            />
          </div>

          <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-neutral-100">
            <button
              type="button"
              @click="closeModal"
              class="px-5 py-3 text-neutral-500 font-bold uppercase tracking-wide text-xs hover:bg-neutral-100 rounded-lg transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="px-5 py-3 bg-[#E8751A] text-white font-bold uppercase tracking-wide text-xs rounded-lg hover:bg-[#d66a15] shadow-md"
            >
              Guardar
            </button>
          </div>
        </form>
      </div>
    </div>

    <div
      v-if="showOrderModal"
      class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4"
    >
      <div
        class="bg-white rounded-2xl p-6 md:p-8 w-full max-w-lg shadow-2xl max-h-[90vh] flex flex-col"
      >
        <div class="flex justify-between items-center border-b pb-4 mb-4 shrink-0">
          <h2 class="text-lg md:text-xl font-bold text-neutral-800 uppercase tracking-wide">
            Pedido #{{ selectedOrder.id.toString().padStart(4, '0') }}
          </h2>
          <button
            @click="showOrderModal = false"
            class="text-neutral-400 hover:text-red-500 font-bold text-xl"
          >
            ✕
          </button>
        </div>
        <div
          class="mb-6 bg-neutral-50 p-4 rounded-xl border border-neutral-200 text-sm text-neutral-800 shrink-0"
        >
          <p class="mb-1.5 flex items-center">
            <span class="font-bold text-neutral-400 w-20 uppercase text-[10px] tracking-wider"
              >Cliente:</span
            ><span class="font-bold text-neutral-800">{{ selectedOrder.customer_name }}</span>
          </p>
          <p class="mb-1.5 flex items-center">
            <span class="font-bold text-neutral-400 w-20 uppercase text-[10px] tracking-wider"
              >Celular:</span
            ><span class="font-bold text-neutral-800">{{ selectedOrder.customer_phone }}</span>
          </p>
          <p class="flex items-center">
            <span class="font-bold text-neutral-400 w-20 uppercase text-[10px] tracking-wider"
              >Dirección:</span
            ><span class="font-bold text-neutral-800">{{ selectedOrder.customer_address }}</span>
          </p>
        </div>
        <h3 class="text-xs font-bold text-neutral-500 uppercase tracking-widest mb-3 shrink-0">
          Rosas Solicitadas
        </h3>
        <ul class="space-y-3 mb-6 overflow-y-auto pr-2 flex-grow">
          <li
            v-for="item in selectedOrder.items"
            :key="item.id"
            class="flex flex-col sm:flex-row justify-between sm:items-center bg-white border border-neutral-200 p-3 rounded-lg gap-2"
          >
            <div>
              <p class="font-bold text-neutral-800">{{ item.quantity }}x {{ item.product_name }}</p>
              <p class="text-xs text-neutral-500">Precio unitario: S/ {{ item.price }}</p>
            </div>
            <p class="font-bold text-[#E8751A]">S/ {{ (item.price * item.quantity).toFixed(2) }}</p>
          </li>
        </ul>
        <div class="flex justify-between items-center pt-4 border-t border-neutral-200 shrink-0">
          <span class="font-bold text-neutral-600">Total a Cobrar:</span>
          <span class="text-xl md:text-2xl font-black text-[#E8751A]"
            >S/ {{ selectedOrder.total_amount }}</span
          >
        </div>
      </div>
    </div>

    <div
      v-if="showStatusConfirmModal"
      class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4"
    >
      <div class="bg-white rounded-3xl p-6 md:p-8 w-full max-w-sm shadow-2xl text-center">
        <div
          class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-5"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
            ></path>
          </svg>
        </div>
        <h2 class="text-xl font-black text-neutral-800 mb-2 tracking-tight">¿Estás seguro?</h2>
        <p class="text-sm text-neutral-500 mb-8 leading-relaxed">
          Vas a marcar este pedido como
          <span class="font-bold text-neutral-800">{{ pendingStatusUpdate.status }}</span
          >. Esta acción no se puede deshacer.
        </p>
        <div class="flex space-x-3">
          <button
            @click="showStatusConfirmModal = false"
            class="flex-1 px-4 py-3 bg-neutral-100 text-neutral-600 font-bold text-xs uppercase tracking-wide rounded-xl"
          >
            Cancelar
          </button>
          <button
            @click="executeOrderStatusUpdate"
            :class="[
              'flex-1 px-4 py-3 font-bold text-xs uppercase tracking-wide rounded-xl text-white shadow-md',
              pendingStatusUpdate.status === 'Pagado'
                ? 'bg-green-500 hover:bg-green-600'
                : 'bg-red-500 hover:bg-red-600',
            ]"
          >
            Confirmar
          </button>
        </div>
      </div>
    </div>

    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm flex items-center justify-center z-50 transition-all p-4"
    >
      <div class="bg-white rounded-3xl p-6 md:p-8 w-full max-w-sm shadow-2xl text-center">
        <div
          class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-5"
        >
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            ></path>
          </svg>
        </div>
        <h2 class="text-xl font-black text-neutral-800 mb-2 tracking-tight">Eliminar Variedad</h2>
        <p class="text-sm text-neutral-500 mb-8 leading-relaxed">
          ¿Estás seguro de eliminar
          <span class="font-bold text-neutral-800">"{{ productToDelete?.name }}"</span>? No podrás
          recuperarla.
        </p>
        <div class="flex space-x-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 px-4 py-3 bg-neutral-100 text-neutral-600 font-bold text-xs uppercase tracking-wide rounded-xl"
          >
            Cancelar
          </button>
          <button
            @click="executeDeleteProduct"
            class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 font-bold text-xs uppercase tracking-wide rounded-xl text-white shadow-md"
          >
            Sí, Eliminar
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

const apiUrl = import.meta.env.VITE_API_URL;

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
  stock: '',
  category_id: '',
  price_50: '',
  price_60: '',
  price_70: '',
  price_80: '',
  price_90: '',
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

// --- AUTENTICACIÓN ---
const getAuthHeaders = () => {
  const token = localStorage.getItem('auth_token')
  return { Authorization: `Bearer ${token}` }
}

// --- LÓGICA DE NAVEGACIÓN ---
const switchModule = (module) => {
  currentModule.value = module
  isMobileMenuOpen.value = false // Cierra el menú lateral en celular
  if (module === 'pedidos') fetchOrders()
  if (module === 'catalogo') fetchProducts()
}

// --- FUNCIONES DEL CATÁLOGO ---
const fetchProducts = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}api/products`, {
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
    name: '',
    stock: '',
    category_id: '',
    price_50: '',
    price_60: '',
    price_70: '',
    price_80: '',
    price_90: '',
  }
  imageFile.value = null
  showModal.value = true
}

const editProduct = (product) => {
  isEditing.value = true
  editingId.value = product.id
  newProduct.value = {
    name: product.name,
    stock: product.stock,
    category_id: product.category_id,
    price_50: product.price_50,
    price_60: product.price_60,
    price_70: product.price_70,
    price_80: product.price_80,
    price_90: product.price_90,
  }
  imageFile.value = null
  showModal.value = true
}

const saveProduct = async () => {
  try {
    const formData = new FormData()
    formData.append('category_id', newProduct.value.category_id)
    formData.append('name', newProduct.value.name)
    formData.append('stock', newProduct.value.stock)
    formData.append('price_50', newProduct.value.price_50)
    formData.append('price_60', newProduct.value.price_60)
    formData.append('price_70', newProduct.value.price_70)
    formData.append('price_80', newProduct.value.price_80)
    formData.append('price_90', newProduct.value.price_90)
    if (imageFile.value) formData.append('image', imageFile.value)

    if (isEditing.value) {
      formData.append('_method', 'PUT')
      await axios.post(`${import.meta.env.VITE_API_URL}api/products/${editingId.value}`, formData, {
        headers: { ...getAuthHeaders(), 'Content-Type': 'multipart/form-data' },
      })
      toast.success('Variedad actualizada correctamente')
    } else {
      await axios.post(`${import.meta.env.VITE_API_URL}api/products`, formData, {
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
    name: '',
    stock: '',
    category_id: '',
    price_50: '',
    price_60: '',
    price_70: '',
    price_80: '',
    price_90: '',
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
    await axios.delete(`${import.meta.env.VITE_API_URL}api/products/${productToDelete.value.id}`, {
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
    const response = await axios.get(`${import.meta.env.VITE_API_URL}api/orders`, {
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
      `${import.meta.env.VITE_API_URL}api/orders/${id}/status`,
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
  fetchProducts()
})
</script>
