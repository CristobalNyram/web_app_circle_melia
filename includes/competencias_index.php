<div class="page-container">

    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Gestión de Competencias</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Competencias</a>
                    <span class="breadcrumb-item active">Gestión de Competencias</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Tabla de Competencias</h4>
                <button class="btn btn-primary m-b-10" data-toggle="modal" data-target="#competenciaModal" onclick="abrirModalCrearCompetencia()">Agregar Nueva Competencia</button>
                <div class="m-t-25">
                    <table id="tabla-competencias" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Competencia</th>
                                <th>Meta de Ventas</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- El contenido será generado dinámicamente por JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wrapper END -->

    <!-- Modal de Competencia START -->
    <div class="modal fade" id="competenciaModal" tabindex="-1" role="dialog" aria-labelledby="competenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="competenciaModalLabel">Agregar/Editar Competencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-competencia">
                        <input type="hidden" id="competenciaId">
                        <div class="form-group">
                            <label for="nombreCompetencia">Nombre Competencia</label>
                            <input type="text" class="form-control" id="nombreCompetencia" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label for="metaVentas">Meta de Ventas</label>
                            <input type="number" class="form-control" id="metaVentas" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicio">Fecha Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaFin">Fecha Fin</label>
                            <input type="date" class="form-control" id="fechaFin" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCompetencia()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Competencia END -->

</div>

<!-- JavaScript CRUD functionality -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        cargarCompetencias();

        $('#competenciaModal').on('hidden.bs.modal', function () {
            limpiarFormulario();
        });
    });

    function cargarCompetencias() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=list'; ?>";

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let data = res.data ?? [];
                const tableBody = document.querySelector('#tabla-competencias tbody');
                tableBody.innerHTML = '';
                data.forEach(competencia => {
                    const row = `<tr>
                        <td>${competencia.idCompetencia}</td>
                        <td>${competencia.nombreCompetencia}</td>
                        <td>${competencia.metaVentas}</td>
                        <td>${competencia.fechaInicio}</td>
                        <td>${competencia.fechaFin}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarCompetencia(${competencia.idCompetencia})">Editar</button>
                            <button hidden class="btn btn-danger btn-sm" onclick="eliminarCompetencia(${competencia.idCompetencia})">Eliminar</button>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
                $('#tabla-competencias').DataTable(); // Inicializar DataTables si no está ya inicializado
            });
    }

    function abrirModalCrearCompetencia() {
        document.getElementById('competenciaModalLabel').innerText = 'Agregar Nueva Competencia';
        limpiarFormulario();
    }

    function guardarCompetencia() {
        const competenciaId = document.getElementById('competenciaId').value;
        const nombreCompetencia = document.getElementById('nombreCompetencia').value;
        const metaVentas = document.getElementById('metaVentas').value;
        const fechaInicio = document.getElementById('fechaInicio').value;
        const fechaFin = document.getElementById('fechaFin').value;

        if (nombreCompetencia.length > 100) {
            Swal.fire({
                title: 'Error',
                text: 'El nombre de la competencia no puede tener más de 100 caracteres',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!fechaInicio || !fechaFin) {
            Swal.fire({
                title: 'Error',
                text: 'Debe ingresar una fecha de inicio y una fecha de fin',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const metodo = competenciaId ? 'POST' : 'POST';
        let api = competenciaId 
            ? `<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=save'; ?>`
            : "<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=save'; ?>";

        let dataJson = JSON.stringify({ competenciaId: competenciaId, nombreCompetencia: nombreCompetencia, metaVentas: metaVentas, fechaInicio: fechaInicio, fechaFin: fechaFin });

        fetch(api, {
            method: metodo,
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
            .then(response => response.json())
            .then((res) => {
                if (res.status) {
                    Swal.fire({
                        title: 'Éxito',
                        text: res.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: res.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
                $('#competenciaModal').modal('hide');
                cargarCompetencias();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al guardar la competencia',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
    }

    function editarCompetencia(idCompetencia) {
        let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=get&idCompetencia='; ?>${idCompetencia}`;

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let competencia = res.data ?? [];
                document.getElementById('competenciaId').value = competencia.idCompetencia;
                document.getElementById('nombreCompetencia').value = competencia.nombreCompetencia;
                document.getElementById('metaVentas').value = competencia.metaVentas;
                document.getElementById('fechaInicio').value = competencia.fechaInicio;
                document.getElementById('fechaFin').value = competencia.fechaFin;
                document.getElementById('competenciaModalLabel').innerText = 'Editar Competencia';
                $('#competenciaModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    function eliminarCompetencia(idCompetencia) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=delete&idCompetencia='; ?>${idCompetencia}`;

                fetch(api, { method: 'DELETE' })
                    .then(response => response.json())
                    .then((res) => {
                        if (res.status) {
                            Swal.fire(
                                'Eliminado',
                                res.message || 'La competencia ha sido eliminada',
                                'success'
                            );
                            cargarCompetencias();
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: res.message || 'No se pudo eliminar la competencia',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al eliminar la competencia',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    }

    function limpiarFormulario() {
        document.getElementById('competenciaId').value = '';
        document.getElementById('nombreCompetencia').value = '';
        document.getElementById('metaVentas').value = '';
        document.getElementById('fechaInicio').value = '';
        document.getElementById('fechaFin').value = '';
    }
</script>
