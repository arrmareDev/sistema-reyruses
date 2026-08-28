<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí definimos explícitamente qué orígenes pueden llamar a la API.
    | Sin este archivo, Laravel usa un default permisivo implícito — mejor
    | dejarlo explícito para saber exactamente qué está permitido.
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Desarrollo local (Vite). Si corres admin y landing a la vez,
        // Vite usa el siguiente puerto libre (5174, 5175...) — agrega el que te asigne.
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:5174',
        'http://127.0.0.1:5174',

        // Producción — AJUSTA estos dos a los dominios reales de tu landing y tu admin
        'https://reyruses.com',
        'https://www.reyruses.com',
    ],

    // Cubre cualquier subdominio de reyruses.com (admin.reyruses.com, panel.reyruses.com, etc.)
    // sin tener que listar cada uno a mano.
    'allowed_origins_patterns' => [
        '#^https://([a-z0-9-]+\.)?reyruses\.com$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // false porque usas tokens de Sanctum (Authorization: Bearer), no cookies de sesión.
    'supports_credentials' => false,

];
