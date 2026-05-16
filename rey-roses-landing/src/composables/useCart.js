import { ref, computed } from 'vue';
import axios from 'axios'; // <-- NUEVO: Necesitamos axios para enviar el pedido

const cart = ref([]);

export function useCart() {
  
  const addToCart = (product) => {
    const existingItem = cart.value.find(item => item.id === product.id);
    if (existingItem) {
      existingItem.quantity += 1;
    } else {
      cart.value.push({ ...product, quantity: 1 });
    }
  };

  const decreaseQuantity = (productId) => {
    const existingItem = cart.value.find(item => item.id === productId);
    if (existingItem) {
      existingItem.quantity -= 1;
      if (existingItem.quantity === 0) {
        removeFromCart(productId);
      }
    }
  };

  const removeFromCart = (productId) => {
    cart.value = cart.value.filter(item => item.id !== productId);
  };

  const clearCart = () => {
    cart.value = [];
  };

  const totalItems = computed(() => {
    return cart.value.reduce((total, item) => total + item.quantity, 0);
  });

  const cartTotal = computed(() => {
    return cart.value.reduce((total, item) => total + (parseFloat(item.price) * item.quantity), 0);
  });

  // NUEVO: Convertimos la función a 'async' para poder esperar a que Laravel responda
const sendWhatsAppOrder = async (customer) => {
    if (cart.value.length === 0) return;

    try {
      // 1. EMPAQUETAMOS LOS DATOS PARA LARAVEL (Limpiando los IDs)
      const payload = {
        // Enviamos los datos del cliente de ambas formas por si tu backend usa una u otra
        customer_name: customer.name,
        customer_phone: customer.phone,
        customer_address: customer.address,
        customer: { 
          name: customer.name,
          phone: customer.phone,
          address: customer.address
        },
        total_amount: cartTotal.value,
        total: cartTotal.value,
        
        // Limpiamos los items del carrito
        items: cart.value.map(item => {
          // Extraemos solo el ID numérico original (De "15-70" sacamos solo el "15")
          const realId = parseInt(String(item.id).split('-')[0]);
          return {
            product_id: realId,
            id: realId,
            product_name: item.name, // Ya incluye el "(70cm)"
            name: item.name,
            price: item.price,
            quantity: item.quantity
          }
        })
      };

      // 2. Enviamos el pedido a la Base de Datos
      await axios.post(import.meta.env.VITE_API_URL + '/api/orders', payload);
      console.log("¡Pedido registrado exitosamente en el panel!");
      
    } catch (error) {
      console.error("Error guardando el pedido en la BD:", error);
      // Opcional: Puedes quitar el // de abajo si quieres que te avise si hay error
      // alert("Hubo un error al guardar en la BD, revisa la consola.");
    }

    // 3. GENERAMOS EL MENSAJE DE WHATSAPP (Se envía aunque falle la BD)
    const numeroWhatsApp = "51902311904"; 
    let textoMensaje = `¡Hola Rey Roses! 🌹 Me gustaría realizar el siguiente pedido:\n\n`;
    
    textoMensaje += `*Datos de Envío:*\n`;
    textoMensaje += `👤 Nombre: ${customer.name}\n`;
    textoMensaje += `📱 Celular: ${customer.phone}\n`;
    textoMensaje += `📍 Dirección: ${customer.address}\n\n`;

    textoMensaje += `*Detalle del Pedido:*\n`;
    cart.value.forEach(item => {
      const subtotal = parseFloat(item.price) * item.quantity;
      textoMensaje += `▫️ ${item.quantity}x ${item.name} a S/ ${item.price} = *S/ ${subtotal.toFixed(2)}*\n`;
    });

    textoMensaje += `\n*💰 TOTAL A PAGAR: S/ ${cartTotal.value.toFixed(2)}*\n\n`;
    textoMensaje += `Quedo a la espera para coordinar el pago y envío.`;

    const mensajeCodificado = encodeURIComponent(textoMensaje);
    const url = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${mensajeCodificado}`;

    window.open(url, '_blank');
    clearCart();
  };

  return {
    cart,
    totalItems,
    cartTotal, 
    addToCart,
    decreaseQuantity,
    removeFromCart,
    clearCart,
    sendWhatsAppOrder
  };
}