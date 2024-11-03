<!-- Side Nav START -->
<div class="side-nav">
    <div class="side-nav-inner">
        <?php if (isset($_SESSION['tipo'])): ?>
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown">
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Inicio</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <!-- Grupo de Herramientas Financieras -->
                    <li class="dropdown-header">Herramientas Financieras</li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'moneda' ? 'active' : '' ?>">
                        <a href="moneda">Cálculo de inflación</a>
                    </li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'travel-tool-projection' ? 'active' : '' ?>">
                        <a href="travel-tool-projection">Travel Tool Projection</a>
                    </li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'no-brainer' ? 'active' : '' ?>">
                        <a href="no-brainer">No Brainer</a>
                    </li>

                    <!-- Grupo de Matrices de Equivalencias -->
                    <li class="dropdown-header">Matrices de Equivalencias</li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'semanas-hoteles-equivalencias' ? 'active' : '' ?>">
                        <a href="semanas-hoteles-equivalencias">Matriz de Equivalencias</a>
                    </li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'membresias-alcance' ? 'active' : '' ?>">
                        <a href="membresias-alcance">Matriz de Equivalencias Membresías</a>
                    </li>

                    <!-- Grupo de Eventos y Competencias -->
                    <li class="dropdown-header">Eventos y Competencias</li>
                    <li class="<?= basename($_SERVER['REQUEST_URI']) == 'competencias-equipos' ? 'active' : '' ?>">
                        <a href="pages/competencias-equipos">Members Fest Ranking</a>
                    </li>

                    <!-- Sección Condicional -->
                    <?php if (isset($_SESSION['tipo'])): ?>
                        <?php if ($_SESSION['tipo'] == 'equipo'): ?>
                            <li class="<?= basename($_SERVER['REQUEST_URI']) == 'ventas-equipo' ? 'active' : '' ?>">
                                <a href="pages/ventas-equipo">Ventas de mi equipo</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </li>
            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'admin'): ?>
                <li class="nav-item dropdown">
                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                            <i class="anticon anticon-tool"></i>
                        </span>
                        <span class="title">Complementos</span>
                        <span class="arrow">
                            <i class="arrow-icon"></i>
                        </span>
                    </a>

                    <ul class="dropdown-menu">
                        <li
                        class="<?= basename($_SERVER['REQUEST_URI']) == 'competencias' ? 'active' : '' ?>"
                        >
                            <a href="pages/competencias/">Competencias</a>
                        </li>
                        <li
                        class="<?= basename($_SERVER['REQUEST_URI']) == 'equipos' ? 'active' : '' ?>"
                        >
                            <a href="pages/equipos/">Equipos</a>
                        </li>
                        <li
                        class="<?= basename($_SERVER['REQUEST_URI']) == 'asociaciones' ? 'active' : '' ?>"
                        >
                            <a href="pages/asociaciones/">Asociaciones</a>
                        </li>
                        <li
                        class="<?= basename($_SERVER['REQUEST_URI']) == 'usuarios' ? 'active' : '' ?>"
                        >
                            <a href="pages/usuarios/">Usuarios</a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>


        </ul>

        <?php endif; ?>

    </div>
</div>
<!-- Side Nav END -->