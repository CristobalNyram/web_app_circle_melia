<?php

// Verifica si la sesión está activa
$is_logged_in = isset($_SESSION['tipo']); // Cambia 'user_id' por el identificador de tu sesión
?>

<div class="page-container">

    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">App</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <center>
                    <h4 class="">Bienvenido a Travel Tool Projection</h4>
                </center>
                <div class="d-flex justify-content-center mt-2">
                    <img 
                        class="logo-fold w-20" 
                        src="https://puntacana-bavaro.com/wp-content/uploads/2017/01/CircleMelia.png" 
                        alt="Logo">
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <!-- Link dinámico basado en el estado de la sesión -->
                    <?php if ($is_logged_in): ?>
                        <a href="travel-tool-projection" class="btn btn-primary">Ir al Travel Tool Projection</a>
                    <?php else: ?>
                        <a href="pages/login/admin" class="btn btn-secondary">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Wrapper END -->

</div>
