<?php
session_start();
define('ROOT_PATH','../../');
define('ROOT_PATH_ASSETS','../../');
include(ROOT_PATH . 'app/config/env.php');

// Destruir todas las variables de sesión
$_SESSION = [];

// Destruir la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redireccionar al login o página de autenticación
header('Location: ' . BASE_URL_PROJECT . 'pages/login/');

exit;
?>
