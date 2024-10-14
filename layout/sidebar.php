<!-- Side Nav START -->
<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">
            <li class="nav-item dropdown open">
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
                    <li hidden class="">
                        <a href="moneda">Cálculo de inflación</a>
                    </li>
                    <li class="">
                        <a href="presupuesto">Cálculo de presupuesto con inflación</a>
                    </li>
                    <li class="">
                        <a href="no-brainer">No brainer</a>
                    </li>
                    <li class="">
                        <a href="locations">Nuestra presencia en el mundo
                        </a>
                    </li>
                    <li class="">
                        <a href="pages/competencias-equipos">Competencias equipos
                        </a>
                    </li>

                    <?php if (isset($_SESSION['tipo'])): ?>
                        <?php if ($_SESSION['tipo'] == 'admin'): ?>
                            <li class="">
                                <a href="pages/competencias/">Comptencias</a>
                            </li>
                            <li class="">
                                <a href="pages/equipos/">Equipos</a>
                            </li>
                            <li class="">
                                <a href="pages/asociaciones/">Asociaciones</a>
                            </li>
                            <li class="">
                                <a href="pages/usuarios/">Usuarios</a>
                            </li>
                        <?php elseif ($_SESSION['tipo'] == 'equipo'): ?>
                            <li class="">
                                <a href="pages/ventas-equipo">Ventas de mi equipo</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- Side Nav END -->
