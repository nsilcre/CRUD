<?php
// Configuración común de sesión y CSRF para toda la aplicación.

session_set_cookie_params([
    'lifetime' => 7200,          // vida de la cookie de sesión (segundos)
    'path'     => '/',           // disponible para toda la aplicación
    'httponly' => true,          // no accesible desde JavaScript
    'samesite' => 'Strict',      // mitiga ataques CSRF básicos
]);

session_start();

// Regeneración periódica del ID de sesión para evitar fijación de sesión.
$regenerate_interval = 1200; // 20 minutos
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
}
if (time() - $_SESSION['last_regeneration'] >= $regenerate_interval) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// CSRF token global: se usa en formularios sensibles (login, etc.).
if (empty($_SESSION['csrf_token'])) {
    $csrf_token = bin2hex(openssl_random_pseudo_bytes(64));
    $_SESSION['csrf_token'] = $csrf_token;
}
