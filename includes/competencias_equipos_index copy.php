<div class="page-container">
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Ventas por Competencia</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Competencias</a>
                    <span class="breadcrumb-item active">Ventas por Competencia</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Seleccionar Competencia</h4>
                <form id="formulario-competencia">
                    <div class="form-group">
                        <label for="competenciaId">Seleccionar Competencia</label>
                        <select class="form-control" id="competenciaId" required>
                            <!-- Opciones cargadas dinámicamente -->
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="iniciarActualizacionVentas()">Mostrar Ventas</button>
                </form>
            </div>
        </div>

        <!-- Sección de Cards para mostrar las ventas por equipo -->
        <div class="container mt-4" id="cards-container">
            <!-- Cards dinámicos generados aquí -->
        </div>
    </div>
    <!-- Content Wrapper END -->
</div>

<!-- Modal para detalles de ventas -->
<div class="modal fade" id="detalleVentasModal" tabindex="-1" role="dialog" aria-labelledby="detalleVentasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleVentasModalLabel">Detalle de Ventas del Equipo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Venta</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Nombre Vendedor</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="detalleVentasBody">
                        <!-- Detalles de ventas cargados dinámicamente aquí -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript functionality -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let intervaloActualizacion;

    document.addEventListener('DOMContentLoaded', function () {
        cargarCompetencias(); // Cargar las competencias disponibles en el select
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

    function iniciarActualizacionVentas() {
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

        // Cargar ventas por primera vez
        cargarVentasCompetencia();

        // Limpiar cualquier intervalo previo
        if (intervaloActualizacion) {
            clearInterval(intervaloActualizacion);
        }

        // Configurar recarga automática entre 60 y 120 segundos
        const tiempoRecarga = Math.floor(Math.random() * (120 - 60 + 1) + 60) * 1000;
        intervaloActualizacion = setInterval(cargarVentasCompetencia, tiempoRecarga);
    }

    function cargarVentasCompetencia() {
        const competenciaId = document.getElementById('competenciaId').value;

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
                mostrarVentasEnCards(res.data);
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

    function mostrarVentasEnCards(equipos) {
        const cardsContainer = document.getElementById('cards-container');
        cardsContainer.innerHTML = ''; // Limpiar contenido previo

        equipos.forEach(equipo => {
            const card = `
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Equipo: ${equipo.nombreEquipo}</h5>
                        <p class="card-text">Ventas: ${equipo.ventasAcumuladas}</p>
                        <p class="card-text">Meta de ventas: 120</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: ${equipo.ventasAcumuladas / 120 * 100}%" aria-valuenow="${equipo.ventasAcumuladas}" aria-valuemin="0" aria-valuemax="120"></div>
                        </div>
                        <button class="btn btn-info" onclick="consultarDetalleVentas(${equipo.idEquipo})">Ver Detalles de Ventas</button>
                    </div>
                </div>`;
            cardsContainer.insertAdjacentHTML('beforeend', card);
        });
    }

    function consultarDetalleVentas(equipoId) {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/ventas/?action=detalleVentas'; ?>";
        let dataJson = JSON.stringify({ equipoId: equipoId });

        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            if (res.status) {
                // Mostrar detalles de ventas en una tabla dentro del modal
                mostrarDetallesEnTabla(res.data);
                $('#detalleVentasModal').modal('show');
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

    function mostrarDetallesEnTabla(ventas) {
        const detalleBody = document.getElementById('detalleVentasBody');
        detalleBody.innerHTML = ''; // Limpiar contenido previo

        ventas.forEach(venta => {
            const row = `
                <tr>
                    <td>${venta.idVenta}</td>
                    <td>${venta.monto}</td>
                    <td>${venta.fecha}</td>
                    <td>${venta.nombreVendedor}</td>
                    <td>${venta.estado}</td>
                </tr>`;
            detalleBody.insertAdjacentHTML('beforeend', row);
        });
    }
</script>
