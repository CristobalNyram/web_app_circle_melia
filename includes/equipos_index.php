<div class="page-container">

    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Gestión de Equipos</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Equipos</a>
                    <span class="breadcrumb-item active">Gestión de Equipos</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Tabla de Equipos</h4>
                <button class="btn btn-primary m-b-10" data-toggle="modal" data-target="#equipoModal" onclick="abrirModalCrearEquipo()">Agregar Nuevo Equipo</button>
                <div class="m-t-25">
                    <table id="tabla-equipos" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Equipo</th>
                                <th>Password del Equipo</th>
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

    <!-- Modal de Equipo START -->
    <div class="modal fade" id="equipoModal" tabindex="-1" role="dialog" aria-labelledby="equipoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="equipoModalLabel">Agregar/Editar Equipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-equipo">
                        <input type="hidden" id="equipoId">
                        <div class="form-group">
                            <label for="nombreEquipo">Nombre del Equipo</label>
                            <input type="text" class="form-control" id="nombreEquipo" maxlength="100" required>
                        </div>
                        <div class="form-group">
                            <label for="passwordEquipo">Password del Equipo</label>
                            <input type="password" class="form-control" id="passwordEquipo" maxlength="255" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEquipo()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Equipo END -->

</div>

<!-- JavaScript CRUD functionality for Equipos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        cargarEquipos();

        $('#equipoModal').on('hidden.bs.modal', function () {
            limpiarFormulario();
        });
    });

    function cargarEquipos() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=list'; ?>";

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let data = res.data ?? [];
                const tableBody = document.querySelector('#tabla-equipos tbody');
                tableBody.innerHTML = '';
                data.forEach(equipo => {
                    const row = `<tr>
                        <td>${equipo.idEquipo}</td>
                        <td>${equipo.nombreEquipo}</td>
                        <td>${equipo.password}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarEquipo(${equipo.idEquipo})">Editar</button>
                            <button hidden class="btn btn-danger btn-sm" onclick="eliminarEquipo(${equipo.idEquipo})">Eliminar</button>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
                $('#tabla-equipos').DataTable(); // Inicializar DataTables si no está ya inicializado
            });
    }

    function abrirModalCrearEquipo() {
        document.getElementById('equipoModalLabel').innerText = 'Agregar Nuevo Equipo';
        limpiarFormulario();
    }

    function guardarEquipo() {
        const equipoId = document.getElementById('equipoId').value;
        const nombreEquipo = document.getElementById('nombreEquipo').value;
        const passwordEquipo = document.getElementById('passwordEquipo').value;

        if (nombreEquipo.length > 100) {
            Swal.fire({
                title: 'Aviso',
                text: 'El nombre del equipo no puede tener más de 100 caracteres',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (passwordEquipo === "") {
            Swal.fire({
                title: 'Aviso',
                text: 'Debe ingresar un password para el equipo',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const metodo = equipoId ? 'POST' : 'POST';
        let api = equipoId 
            ? `<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=save'; ?>`
            : "<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=save'; ?>";

        let dataJson = JSON.stringify({ equipoId: equipoId, nombreEquipo: nombreEquipo, password: passwordEquipo });

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
                        title: 'Aviso',
                        text: res.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
                $('#equipoModal').modal('hide');
                cargarEquipos();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Aviso',
                    text: 'Hubo un error al guardar el equipo',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            });
    }

    function editarEquipo(idEquipo) {
        let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=get&idEquipo='; ?>${idEquipo}`;

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let equipo = res.data ?? [];
                document.getElementById('equipoId').value = equipo.idEquipo;
                document.getElementById('nombreEquipo').value = equipo.nombreEquipo;
                document.getElementById('passwordEquipo').value = equipo.password;
                document.getElementById('equipoModalLabel').innerText = 'Editar Equipo';
                $('#equipoModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    function eliminarEquipo(idEquipo) {
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
                let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=delete&idEquipo='; ?>${idEquipo}`;

                fetch(api, { method: 'DELETE' })
                    .then(response => response.json())
                    .then((res) => {
                        if (res.status) {
                            Swal.fire(
                                'Eliminado',
                                res.message || 'El equipo ha sido eliminado',
                                'success'
                            );
                            cargarEquipos();
                        } else {
                            Swal.fire({
                                title: 'Aviso',
                                text: res.message || 'No se pudo eliminar el equipo',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Aviso',
                            text: 'Hubo un error al eliminar el equipo',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    }

    function limpiarFormulario() {
        document.getElementById('equipoId').value = '';
        document.getElementById('nombreEquipo').value = '';
        document.getElementById('passwordEquipo').value = '';
    }
</script>
