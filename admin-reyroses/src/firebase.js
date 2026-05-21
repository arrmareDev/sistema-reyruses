import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

const firebaseConfig = {
  apiKey: "AIzaSyArLvoS2dvQmRedp5xcVKikO0MImb6PMYo",
  authDomain: "sistema-rey-ruses.firebaseapp.com",
  projectId: "sistema-rey-ruses",
  storageBucket: "sistema-rey-ruses.firebasestorage.app",
  messagingSenderId: "55421219973",
  appId: "1:55421219973:web:6b277cf75933c28af925ec"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export const requestForToken = async () => {
  try {
    console.log("1. Registrando SW...");
    const registration = await navigator.serviceWorker.register(
      "/firebase-messaging-sw.js"
    );
    console.log("2. SW registrado:", registration);

    console.log("3. Esperando SW ready...");
    const readyReg = await navigator.serviceWorker.ready;
    console.log("4. SW listo:", readyReg);

    const currentToken = await getToken(messaging, {
      vapidKey: "BAOrjzUcNvAQxPNc1MWX_mnP8TAdly50wDqx7CBukc9-XxMFDRwyVvLVoCIPNa_WsIMLPSkSdFcyQGmw6kHZIKE",
      serviceWorkerRegistration: readyReg,
    });

    if (currentToken) {
      console.log("¡Token obtenido!", currentToken);
      return currentToken;
    }
    return null;
  } catch (err) {
    console.error("Error al obtener el token", err);
    return null;
  }
};

export const onMessageListener = (callback) => {
  return onMessage(messaging, (payload) => {
    callback(payload);
  });
};
