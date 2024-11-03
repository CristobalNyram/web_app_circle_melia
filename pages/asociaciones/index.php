<?php
session_start();
define('ROOT_PATH','../../');
define('ROOT_PATH_ASSETS','../../');
include(ROOT_PATH . 'app/config/env.php');
include(ROOT_PATH . 'includes/auth/validacion_sesion.php');
include(ROOT_PATH . 'layout/header-simple.php');
include(ROOT_PATH . 'layout/nav.php');
include(ROOT_PATH . 'layout/sidebar.php');
include(ROOT_PATH . 'includes/asociaciones_index.php');
include(ROOT_PATH . 'layout/footer.php');
include(ROOT_PATH . 'layout/scripts-tablas.php');
?>
