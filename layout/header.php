<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Melia - App's</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">

    <!-- Core css -->
    <link href="assets/css/app.min.css" rel="stylesheet">
    <link href="assets/css/app.custom.css" rel="stylesheet">
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
    <div class="app" 
    >
        <div class="layout">
