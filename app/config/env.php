<?php
    // Definir variables globales para los datos de conexión
    define( "DB_HOST_MAIN", "localhost");
    define("DB_USER_MAIN", "root");
    define("DB_PASSWORD_MAIN", "");
    define("DB_NAME_MAIN", "pruebaapiintegra_ventas_competencias");
    define("DB_PREFIX", "");
    define("BASE_URL_PROJECT", "http://127.0.0.1/midas/web_app_circle_melia/");


    
    // Definir el entorno: 'development' o 'production'
    define("ENVIRONMENT", "development"); // Cambia a 'production' para producción

    define('TOKEN_GLOBAL', '63e2'); // Este 
    // define('TOKEN_GLOBAL', '63e2b9f8d4a8fe21d916bdf217adf1238479c3e67c4f5d9ba8cb2147db5e9fc2ae5673b8c9ad2b6e91fa435bf7db96e8f3c294a25a9d5172f4ed64e83'); 
    // Este es el token global que se utilizará



// Configurar el manejo de errores según el entorno
if (ENVIRONMENT === 'development') {
    // Mostrar todos los errores en desarrollo
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // En producción, no mostrar errores y registrarlos en un log
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    // ini_set('error_log', '/path/to/your/logs/php_error.log'); // Cambia la ruta por la de tu archivo de log
    error_reporting(E_ALL); // Registrar todos los errores
    error_reporting(0); // No reportar ningún error en pantalla

}
