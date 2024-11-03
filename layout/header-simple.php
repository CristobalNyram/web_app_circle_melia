<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    // Define a global variable ROOT_PATH if not already defined
    if (!defined('ROOT_PATH_ASSETS')) {
        define('ROOT_PATH_ASSETS', '../'); // Default path if ROOT_PATH is not defined
    }
    if (!isset($titlePage)) {
        $titlePage = "Melia - App's"; // Definir la variable si no está definida
    }

    ?>

    <base href="<?php echo ROOT_PATH_ASSETS; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Travel Tool Projection: una herramienta innovadora para calcular y proyectar los costos y necesidades de viajes en todo el mundo, optimizada para empresas y usuarios que buscan hacer una planeación eficiente.">
    <meta name="keywords" content="Travel Tool, Proyección de Viajes, planeación de viajes, costos de viaje, proyección financiera, gestión de viajes, empresas, herramientas de viaje, eficiencia en viajes">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Travel Tool Projection - Planeación y Proyección de Viajes">
    <meta property="og:description" content="Optimiza tus viajes con Travel Tool Projection, la herramienta ideal para planear costos y proyecciones de viajes de manera efectiva y precisa.">
    <meta property="og:image" content="assets/images/logo/logo-main-colo.png">
    <meta property="og:url" content="https://traveltoolprojection.com/app/">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Travel Tool Projection - Planeación y Proyección de Viajes">
    <meta name="twitter:description" content="Optimiza tus viajes con Travel Tool Projection, la herramienta ideal para planear costos y proyecciones de viajes de manera efectiva y precisa.">
    <meta name="twitter:image" content="assets/images/logo/logo-main-colo.png">
    <title><?php echo $titlePage; ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">

    <!-- Core css -->
    <link href="assets/css/app.min.css" rel="stylesheet">
    <!-- <link href="assets/css/app.custom.css" rel="stylesheet"> -->

    <!-- page css -->
    <link href="assets/vendors/datatables/dataTables.bootstrap.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.page-container');

            // Lista de URLs de fondo
            const backgrounds = [
                './../images/resources/BACKGROUND-0.jpg',
                './../images/resources/BACKGROUND-1.jpg',
                './../images/resources/BACKGROUND-2.jpg'
            ];

            // Elegir un índice aleatorio
            const randomIndex = Math.floor(Math.random() * backgrounds.length);

            // URL del fondo aleatorio
            const backgroundUrl = backgrounds[randomIndex];

            // Cambiar el fondo del pseudo-elemento ::before usando estilos en línea
            const styleSheet = document.styleSheets[0]; // Obtén la primera hoja de estilo
            const rule = `.page-container::before { background: url('${backgroundUrl}') no-repeat center center; }`;

            // Añadir una regla de estilo dinámica
            styleSheet.insertRule(rule, styleSheet.cssRules.length);
        });
    </script>
</head>

<body>
    <div class="app
    <?php if (!isset($_SESSION['tipo'])): ?>
    is-folded
    <?php endif; ?>
    ">
        <div class="layout">