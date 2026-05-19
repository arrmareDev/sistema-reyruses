import { createApp } from "vue";
import App from "./App.vue";
import "./styles/global.css";

// 👇 Importamos el estilo de las alertas aquí también
import 'vue-sonner/style.css';

document.title = "REY RUSES";

createApp(App).mount("#app");