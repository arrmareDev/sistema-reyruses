import { reactive, computed } from "vue";

function sanitize(value) {
  if (typeof value !== "string") return "";
  return value
    .trim()
    .replace(/[<>]/g, "") // previene XSS básico
    .replace(/javascript:/gi, "") // previene proto-inyección
    .substring(0, 500); // límite de longitud
}

function isValidEmail(email) {
  const pattern = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
  return pattern.test(email);
}

function isValidPhone(phone) {
  if (!phone) return true; // campo opcional
  const pattern = /^[+]?[\d\s\-().]{6,20}$/;
  return pattern.test(phone);
}

export function useForm() {
  const fields = reactive({
    name: { value: "", error: "", touched: false },
    email: { value: "", error: "", touched: false },
    phone: { value: "", error: "", touched: false },
    message: { value: "", error: "", touched: false },
  });

  const status = reactive({
    loading: false,
    success: false,
    error: false,
  });

  function validateField(name) {
    const f = fields[name];
    const val = sanitize(f.value);

    switch (name) {
      case "name":
        if (!val || val.length < 2)
          f.error = "Por favor ingresa tu nombre completo.";
        else f.error = "";
        break;

      case "email":
        if (!val) f.error = "El correo electrónico es requerido.";
        else if (!isValidEmail(val))
          f.error = "Ingresa un correo electrónico válido.";
        else f.error = "";
        break;

      case "phone":
        if (val && !isValidPhone(val))
          f.error = "Ingresa un número de teléfono válido.";
        else f.error = "";
        break;

      case "message":
        if (!val || val.length < 10)
          f.error = "Por favor escribe un mensaje de al menos 10 caracteres.";
        else f.error = "";
        break;
    }
  }

  function touchField(name) {
    fields[name].touched = true;
    validateField(name);
  }

  function validateAll() {
    Object.keys(fields).forEach((name) => {
      fields[name].touched = true;
      validateField(name);
    });
    return Object.keys(fields).every((name) => !fields[name].error);
  }

  const isValid = computed(() =>
    Object.keys(fields).every((name) => !fields[name].error),
  );

  function getSanitizedData() {
    return {
      name: sanitize(fields.name.value),
      email: sanitize(fields.email.value),
      phone: sanitize(fields.phone.value),
      message: sanitize(fields.message.value),
    };
  }

  function reset() {
    Object.keys(fields).forEach((name) => {
      fields[name].value = "";
      fields[name].error = "";
      fields[name].touched = false;
    });
    status.loading = false;
    status.success = false;
    status.error = false;
  }

  async function handleSubmit() {
    if (!validateAll()) return false;

    status.loading = true;
    status.success = false;
    status.error = false;

    try {
      // 1. Obtenemos la data ya limpia y validada
      const data = getSanitizedData();
      
      // 2. Definimos el número destino (Solo números. Ej: 51 para Perú)
      const numeroWhatsApp = "51927571431"; // <-- COLOCA AQUÍ EL NÚMERO DE REY ROSES

      // 3. Construimos el mensaje de forma elegante usando los datos
      const textoMensaje = `¡Hola Rey Roses! 🌹 Me interesa hacer un pedido.\n\n` +
                           `*Nombre:* ${data.name}\n` +
                           `*Correo:* ${data.email}\n` +
                           `*Teléfono:* ${data.phone ? data.phone : 'No especificado'}\n` +
                           `*Mensaje:* ${data.message}`;

      // 4. Codificamos el mensaje para la URL
      const mensajeCodificado = encodeURIComponent(textoMensaje);
      const url = `https://api.whatsapp.com/send?phone=${numeroWhatsApp}&text=${mensajeCodificado}`;
      // (Opcional) Mantenemos un pequeño delay visual para que el botón muestre estado "loading"
      await new Promise((resolve) => setTimeout(resolve, 600));

      // 5. Abrimos WhatsApp en una nueva pestaña
      window.open(url, '_blank');

      // 6. Marcamos como exitoso y reseteamos el formulario
      status.success = true;
      reset();
      return true;
      
    } catch (error) {
      console.error("Error al procesar el envío:", error);
      status.error = true;
      return false;
    } finally {
      status.loading = false;
    }
  }

  return {
    fields,
    status,
    isValid,
    touchField,
    validateField,
    handleSubmit,
    reset,
  };
}