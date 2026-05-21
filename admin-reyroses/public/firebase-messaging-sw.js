/* eslint-disable */
importScripts("https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging-compat.js");

const firebaseConfig = {
  apiKey: "AIzaSyArLvoS2dvQmRedp5xcVKikO0MImb6PMYo",
  authDomain: "sistema-rey-ruses.firebaseapp.com",
  projectId: "sistema-rey-ruses",
  storageBucket: "sistema-rey-ruses.firebasestorage.app",
  messagingSenderId: "55421219973",
  appId: "1:55421219973:web:6b277cf75933c28af925ec"
};


try {
  firebase.initializeApp(firebaseConfig);
  const messaging = firebase.messaging();

  messaging.onBackgroundMessage(function(payload) {
    console.log("¡Mensaje recibido en segundo plano!", payload);
    const notificationTitle = payload.notification?.title || "Nuevo Pedido";
    const notificationOptions = {
      body: payload.notification?.body || "Tienes una nueva alerta en el sistema.",
      icon: "/favicon.ico",
      badge: "/favicon.ico"
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
  });
} catch (error) {
  console.error("Error inicializando el Service Worker de Firebase:", error);
}
