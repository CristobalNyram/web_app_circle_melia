<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Ventas del Equipo: <?php echo htmlspecialchars($_SESSION['nombreEquipo'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Ventas</a>
                    <a class="breadcrumb-item">Equipo</a>
                    <span class="breadcrumb-item active">Competencias y Gestión de Ventas</span>
                </nav>
            </div>
        </div>

        <!-- Mostrar competencias del equipo -->
        <div class="card">
            <div class="card-body">
                <h4>Competencias Inscritas</h4>
                <div id="competencias-container" class="row">
                    <!-- Competencias dinámicas cargadas aquí -->
                </div>
            </div>
        </div>

        <!-- Modales de agregar y editar ventas -->
        <!-- Modal para Agregar Venta -->
        <div class="modal fade" id="modalAgregarVenta" tabindex="-1" role="dialog" aria-labelledby="modalAgregarVentaLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarVentaLabel">Agregar Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formulario-agregar-ventas">
                            <div class="form-group">
                                <label for="integranteAgregar">Integrante del Equipo</label>
                                <select class="form-control" id="integranteAgregar" required></select>
                            </div>
                            <div class="form-group">
                                <label for="montoVentaAgregar">Monto de la Venta</label>
                                <input type="number" class="form-control" id="montoVentaAgregar" required>
                            </div>
                            <div class="form-group">
                                <label for="estatusAgregar">Estado</label>
                                <select class="form-control" id="estatusAgregar" required>
                                    <option value="1">Stand by</option>
                                    <option value="2">Activo</option>
                                    <option value="-2">Cancelado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fechaVentaAgregar">Fecha de la Venta</label>
                                <input type="datetime-local" class="form-control" id="fechaVentaAgregar" required>
                            </div>
                            <button type="button" class="btn btn-success" onclick="guardarVenta()">Guardar Venta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Venta -->
        <div class="modal fade" id="modalEditarVenta" tabindex="-1" role="dialog" aria-labelledby="modalEditarVentaLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarVentaLabel">Editar Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formulario-editar-ventas">
                            <input type="hidden" id="ventaIdEditar" value="">
                            <div class="form-group">
                                <label for="integranteEditar">Integrante del Equipo</label>
                                <select class="form-control" id="integranteEditar" required></select>
                            </div>
                            <div class="form-group">
                                <label for="montoVentaEditar">Monto de la Venta</label>
                                <input type="number" class="form-control" id="montoVentaEditar" required>
                            </div>
                            <div class="form-group">
                                <label for="estatusEditar">Estado</label>
                                <select class="form-control" id="estatusEditar" required>
                                    <option value="1">Stand by</option>
                                    <option value="2">Activo</option>
                                    <option value="-2">Cancelado</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fechaVentaEditar">Fecha de la Venta</label>
                                <input type="datetime-local" class="form-control" id="fechaVentaEditar" required>
                            </div>
                            <button type="button" class="btn btn-success" onclick="editarVenta()">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para gestionar ventas -->
        <div class="modal fade" id="modalGestionarVentas" tabindex="-1" role="dialog" aria-labelledby="modalGestionarVentasLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGestionarVentasLabel">Gestionar Ventas de la Competencia</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Listado de Ventas</h4>
                            <button onclick="mostrarModalAgregarVenta()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarVenta">
                                Agregar Venta
                            </button>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Vendedor</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="detalleVentasBody">
                                <!-- Ventas dinámicas cargadas aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        cargarCompetenciasEquipo();
    });

    let currentCompetenciaId = null;

    function cargarCompetenciasEquipo() {
        const equipoId = "<?php echo $_SESSION['equipoId']; ?>";
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/competencias/?action=listCompetenciasEquipo'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId
        });
        fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: dataJson
            })
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    mostrarCompetencias(res.data);
                    cargarIntegrantesEquipo();
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function mostrarCompetencias(competencias) {
        const container = document.getElementById('competencias-container');
        container.innerHTML = '';

        competencias.forEach(competencia => {
            const card = `
                <div class="col-md-4 col-12 col-lg-6">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">${competencia.nombreCompetencia}</h5>
                            <p class="card-text">Meta de Ventas: ${competencia.metaVentas}</p>
                            <button class="btn btn-info" onclick="gestionarVentasCompetencia(${competencia.idCompetencia})">Gestionar Ventas</button>
                        </div>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', card);
        });
    }

    function gestionarVentasCompetencia(competenciaId) {
        currentCompetenciaId = competenciaId;
        cargarVentasCompetencia(competenciaId);
        $('#modalGestionarVentas').modal('show');
    }

    function cargarIntegrantesEquipo() {
        const equipoId = "<?php echo $_SESSION['equipoId']; ?>";
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/equipos/?action=listIntegrantesEquipo'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId
        });
        fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: dataJson
            })
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    const integrantes = res.data;
                    const selectAgregar = document.getElementById('integranteAgregar');
                    const selectEditar = document.getElementById('integranteEditar');

                    selectAgregar.innerHTML = '';
                    selectEditar.innerHTML = '';

                    integrantes.forEach(integrante => {
                        const option = `<option value="${integrante.idIntegrante}">${integrante.nombre}</option>`;
                        selectAgregar.insertAdjacentHTML('beforeend', option);
                        selectEditar.insertAdjacentHTML('beforeend', option);
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function cargarVentasCompetencia(competenciaId) {
        const equipoId = "<?php echo $_SESSION['equipoId']; ?>";
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=getVentasByEquipoComptencia'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId,
            competenciaId: competenciaId
        });
        fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: dataJson
            })
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    mostrarDetalleVentas(res.data);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error(error));
    }

    function mostrarDetalleVentas(ventas) {
        const detalleBody = document.getElementById('detalleVentasBody');
        detalleBody.innerHTML = '';
        ventas.forEach(venta => {
            const row = `
                <tr>
                    <td>${venta.folio}</td>
                    <td>${venta.nombreVendedor}</td>
                    <td>${venta.monto}</td>
                    <td><span class="badge badge-${getEstadoClass(venta.estado)}">${getEstadoTexto(venta.estado)}</span></td>
                    <td>${venta.fechaVenta}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="cargarModalEditarVenta(${venta.idCompetenciaEquipoVenta}, ${venta.monto}, '${venta.fechaVenta}', ${venta.estado}, ${venta.idIntegrante})">Editar</button>
                    </td>
                </tr>`;
            detalleBody.insertAdjacentHTML('beforeend', row);
        });
    }

        // Cargar modal de editar venta
        function cargarModalEditarVenta(idVenta, monto, fecha, estado, idIntegrante) {
            // Limpiar el formulario de edición
            limpiarFormulario('formulario-editar-ventas');

            // Asignar los valores al formulario
            document.getElementById('ventaIdEditar').value = idVenta;
            document.getElementById('montoVentaEditar').value = monto;
            document.getElementById('fechaVentaEditar').value = fecha;
            document.getElementById('estatusEditar').value = estado;
            document.getElementById('integranteEditar').value = idIntegrante;

            // Cerrar el modal de gestionar ventas antes de abrir el modal de editar
            $('#modalGestionarVentas').modal('hide');

            // Mostrar el modal de edición
            $('#modalEditarVenta').modal('show');
        }

        // Cargar modal de agregar venta
        function mostrarModalAgregarVenta() {
            // Limpiar el formulario de agregar
            limpiarFormulario('formulario-agregar-ventas');

            // Cerrar el modal de gestionar ventas antes de abrir el modal de agregar
            $('#modalGestionarVentas').modal('hide');

            // Mostrar el modal de agregar
            $('#modalAgregarVenta').modal('show');
        }


    function guardarVenta() {
        const montoVenta = document.getElementById('montoVentaAgregar').value;
        const estatus = document.getElementById('estatusAgregar').value;
        const fechaVenta = document.getElementById('fechaVentaAgregar').value;
        const idIntegrante = document.getElementById('integranteAgregar').value;

        if (!montoVenta || !fechaVenta || !estatus || !idIntegrante) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const equipoId = "<?php echo $_SESSION['equipoId']; ?>";
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=guardarVenta'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId,
            competenciaId: currentCompetenciaId,
            monto: montoVenta,
            estatus: estatus,
            fechaVenta: fechaVenta,
            idIntegrante: idIntegrante
        });

        fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
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
                    $('#modalAgregarVenta').modal('hide');
                    cargarVentasCompetencia(currentCompetenciaId);
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

    function editarVenta() {
        const idVenta = document.getElementById('ventaIdEditar').value;
        const montoVenta = document.getElementById('montoVentaEditar').value;
        const estatus = document.getElementById('estatusEditar').value;
        const fechaVenta = document.getElementById('fechaVentaEditar').value;
        const idIntegrante = document.getElementById('integranteEditar').value;

        if (!montoVenta || !fechaVenta || !estatus || !idIntegrante || !idVenta) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const equipoId = "<?php echo $_SESSION['equipoId']; ?>";
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=editarVenta'; ?>";
        let dataJson = JSON.stringify({
            idVenta: idVenta,
            equipoId: equipoId,
            competenciaId: currentCompetenciaId,
            monto: montoVenta,
            estatus: estatus,
            fechaVenta: fechaVenta,
            idIntegrante: idIntegrante
        });

        fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: dataJson
            })
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Venta actualizada correctamente',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    $('#modalEditarVenta').modal('hide');
                    cargarVentasCompetencia(currentCompetenciaId);
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

    function getEstadoTexto(estado) {
        switch (estado) {
            case 1:
                return 'Stand by';
            case 2:
                return 'Activo';
            case -2:
                return 'Cancelado';
            default:
                return 'Desconocido';
        }
    }

    function getEstadoClass(estado) {
        switch (estado) {
            case 1:
                return 'warning';
            case 2:
                return 'success';
            case -2:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    function limpiarFormulario(formId) {
        document.getElementById(formId).reset();
    }
</script>