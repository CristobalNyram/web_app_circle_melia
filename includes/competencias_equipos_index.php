<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Ventas por Competencia, Equipo y Usuario</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Ventas</a>
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
                        <select class="form-control" id="competenciaId" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="cargarVentasCompetencia()">Mostrar Ventas</button>
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

    function mostrarMetaVentas(competencia) {
        const metaContainer = document.getElementById('meta-container');
        metaContainer.innerHTML = `
            <div class="col-12">
                <h3>Meta de Ventas para ${competencia.nombreCompetencia}: ${competencia.metaVentas}</h3>
            </div>
        `;
    }

    function mostrarVentasEnCards(equipos) {
        const cardsContainer = document.getElementById('cards-container');
        cardsContainer.innerHTML = ''; // Limpiar contenido previo

        equipos.forEach(equipo => {
            const progress = (equipo.ventasAcumuladas / equipo.metaVentas) * 100;
            // console.log(equipo);
            const card = `
                <div class="col-md-4 col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Equipo: ${equipo.nombreEquipo}</h5>
                            <p class="card-text">Ventas Totales: ${equipo.ventasAcumuladas}</p>
                            <div class="progress mb-3">
                                <div class="progress-bar" role="progressbar" style="width: ${progress}%;" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <button class="btn btn-info" onclick="verDetalleVentas(${equipo.idEquipo})">Ver Detalle de Ventas</button>
                        </div>
                    </div>
                </div>`;
            cardsContainer.insertAdjacentHTML('beforeend', card);
        });
    }

    function verDetalleVentas(equipoId) {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/ventas/?action=detalleVentas'; ?>";
        let dataJson = JSON.stringify({ equipoId: equipoId, });
        console.log(dataJson);
        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            console.log(res);
            if (res.status) {
                mostrarDetalleVentas(res.data);
                $('#modalDetalleVentas').modal('show');
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

    function mostrarDetalleVentas(ventas) {
    const detalleBody = document.getElementById('detalleVentasBody');
    detalleBody.innerHTML = ''; // Limpiar contenido previo

    ventas.forEach(venta => {
        let badgeColor = '';
        let badgeText = '';

        switch (venta.estado) {
            case 1:
                badgeColor = 'badge-warning'; // Estado en "Stand by"
                badgeText = 'Stand by';
                break;
            case 2:
                badgeColor = 'badge-success'; // Estado "Activo"
                badgeText = 'Activo';
                break;
            case -2:
                badgeColor = 'badge-danger'; // Estado "Cancelado"
                badgeText = 'Cancelado';
                break;
            default:
                badgeColor = 'badge-secondary'; // Estado "Desconocido"
                badgeText = 'Desconocido';
                break;
        }

        const row = `
            <tr>
                <td>${venta.nombreVendedor}</td>
                <td>${venta.monto}</td>
                <td><span class="badge ${badgeColor}">${badgeText}</span></td>
                <td>${venta.fecha}</td>
            </tr>`;
        detalleBody.insertAdjacentHTML('beforeend', row);
    });
}

</script>
