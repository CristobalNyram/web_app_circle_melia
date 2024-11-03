<?php
if (!isset($_SESSION['tipo']) || ($_SESSION['tipo'] !== 'admin' && $_SESSION['tipo'] !== 'invitado')) {
    header('Location: ' . BASE_URL_PROJECT . 'pages/login/admin');
    exit;
}
?>
