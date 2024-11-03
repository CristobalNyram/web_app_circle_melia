<?php
session_start();
define('ROOT_PATH','../');
define('ROOT_PATH_ASSETS','../');
include(ROOT_PATH . 'app/config/env.php');
include(ROOT_PATH . 'includes/auth/validacion_sesion.php');
include('../layout/header.php');
include('../layout/nav.php');
include('../layout/sidebar.php');
include('../layout/content-presupuesto.php');
include('../layout/footer.php');
include('../layout/scripts-presupuesto.php');
?>
