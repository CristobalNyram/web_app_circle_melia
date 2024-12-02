<?php
session_start();
define('ROOT_PATH','../');
define('ROOT_PATH_ASSETS','../');
include(ROOT_PATH . 'app/config/env.php');
include(ROOT_PATH . 'includes/auth/validacion_sesion.php');
include(ROOT_PATH . 'includes/helpers/session.php');
updateSessionByLastView();
include(ROOT_PATH . 'layout/header.php');
include(ROOT_PATH . 'layout/nav.php');
include(ROOT_PATH . 'layout/sidebar.php');
include(ROOT_PATH . 'includes/content-vacation-tool-projection.php');
include(ROOT_PATH . 'layout/footer.php');
include(ROOT_PATH . 'includes/scripts-vacation-tool-projection.php');
?>
