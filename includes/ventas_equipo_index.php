<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Registro y Edición de Ventas</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Ventas</a>
                    <span class="breadcrumb-item active">Registro y Edición de Ventas</span>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Seleccionar Competencia</h4>
                <form id="formulario-competencia">
                    <div class="form-group">
                        <label for="competenciaId">Seleccionar Competencia</label>
                        <select class="form-control" id="competenciaId" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="cargarVentasCompetencia()">Mostrar Ventas</button>
                </form>
            </div>
        </div>

        <!-- Formulario para agregar o editar ventas -->
        <div class="card mt-3">
            <div class="card-body">
                <h4>Agregar/Editar Venta</h4>
                <form id="formulario-ventas">
                    <div class="form-group">
                        <label for="equipoId">Seleccionar Equipo</label>
                        <select class="form-control" id="equipoId" required></select>
                    </div>
                    <div class="form-group">
                        <label for="montoVenta">Monto de la Venta</label>
                        <input type="number" class="form-control" id="montoVenta" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaVenta">Fecha de la Venta</label>
                        <input type="date" class="form-control" id="fechaVenta" required>
                    </div>
                    <button type="button" class="btn btn-success" onclick="guardarVenta()">Guardar Venta</button>
                </form>
            </div>
        </div>

        <!-- Mostrar meta de ventas y equipos -->
        <div class="container mt-4 row" id="meta-container"></div>
        <div class="container mt-4 row" id="cards-container">
            <!-- Cards dinámicos generados aquí -->
        </div>
    </div>
</div>

<!-- Modal para Detalle de Ventas -->
<div class="modal fade" id="modalDetalleVentas" tabindex="-1" role="dialog" aria-labelledby="modalDetalleVentasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleVentasLabel">Detalle de Ventas del Equipo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Vendedor</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="detalleVentasBody">
                        <!-- Contenido dinámico -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        cargarCompetencias(); // Cargar las competencias disponibles en el select
        cargarEquipos(); // Cargar los equipos disponibles en el select
    });

    function cargarCompetencias() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=list'; ?>";
        fetch(api)
            .then(response => response.json())
            .then(res => {
                let competencias = res.data ?? [];
                const competenciaSelect = document.getElementById('competenciaId');
                competencias.forEach(competencia => {
                    let option = document.createElement('option');
                    option.value = competencia.idCompetencia;
                    option.text = competencia.nombreCompetencia;
                    competenciaSelect.add(option);
                });
            });
    }

    function cargarEquipos() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=list'; ?>";
        fetch(api)
            .then(response => response.json())
            .then(res => {
                let equipos = res.data ?? [];
                const equipoSelect = document.getElementById('equipoId');
                equipos.forEach(equipo => {
                    let option = document.createElement('option');
                    option.value = equipo.idEquipo;
                    option.text = equipo.nombreEquipo;
                    equipoSelect.add(option);
                });
            });
    }

    function guardarVenta() {
        const equipoId = document.getElementById('equipoId').value;
        const montoVenta = document.getElementById('montoVenta').value;
        const fechaVenta = document.getElementById('fechaVenta').value;
        const competenciaId = document.getElementById('competenciaId').value;

        if (!equipoId || !montoVenta || !fechaVenta || !competenciaId) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/ventas/?action=guardarVenta'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId,
            monto: montoVenta,
            fechaVenta: fechaVenta,
            competenciaId: competenciaId
        });

        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            if (res.status) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'Venta registrada correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                cargarVentasCompetencia(); // Refrescar las ventas
            } else {
                Swal.fire({
                    title: 'Error',
                    text: res.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema con la conexión',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }

    function cargarVentasCompetencia() {
        const competenciaId = document.getElementById('competenciaId').value;

        if (!competenciaId) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar una competencia',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/ventas/?action=listVentasCompetencia'; ?>";
        let dataJson = JSON.stringify({ competenciaId: competenciaId });

        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            if (res.status) {
                mostrarMetaVentas(res.data.competencia);
                mostrarVentasEnCards(res.data.equipos);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: res.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
