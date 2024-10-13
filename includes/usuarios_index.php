<div class="page-container">

    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Gestión de Usuarios</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Usuarios</a>
                    <span class="breadcrumb-item active">Gestión de Usuarios</span>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4>Tabla de Usuarios</h4>
                <button class="btn btn-primary m-b-10" data-toggle="modal" data-target="#usuarioModal" onclick="abrirModalCrearUsuario()">Agregar Nuevo Usuario</button>
                <div class="m-t-25">
                    <table id="tabla-usuarios" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Tipo</th>
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

    <!-- Modal de Usuario START -->
    <div class="modal fade" id="usuarioModal" tabindex="-1" role="dialog" aria-labelledby="usuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usuarioModalLabel">Agregar/Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-usuario">
                        <input type="hidden" id="usuarioId">
                        <div class="form-group">
                            <label for="nombreUsuario">Nombre Completo</label>
                            <input type="text" class="form-control" id="nombreUsuario" maxlength="55" required>
                        </div>
                        <div class="form-group">
                            <label for="tipoUsuario">Tipo de Usuario</label>
                            <select class="form-control" id="tipoUsuario" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="admin">Administrador</option>
                                <option value="vendedor">Vendedor</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarUsuario()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Usuario END -->

</div>

<!-- JavaScript CRUD functionality -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        cargarUsuarios();

        $('#usuarioModal').on('hidden.bs.modal', function () {
            limpiarFormulario();
        });
    });

    function cargarUsuarios() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=list'; ?>";

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let data = res.data ?? [];
                const tableBody = document.querySelector('#tabla-usuarios tbody');
                tableBody.innerHTML = '';
                data.forEach(usuario => {
                    const row = `<tr>
                        <td>${usuario.idUsuario}</td>
                        <td>${usuario.nombreUsuario}</td>
                        <td>${usuario.tipo}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarUsuario(${usuario.idUsuario})">Editar</button>
                            <button hidden class="btn btn-danger btn-sm" onclick="eliminarUsuario(${usuario.idUsuario})">Eliminar</button>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
                $('#tabla-usuarios').DataTable(); // Inicializar DataTables si no está ya inicializado
            });
    }

    function abrirModalCrearUsuario() {
        document.getElementById('usuarioModalLabel').innerText = 'Agregar Nuevo Usuario';
        limpiarFormulario();
    }

    function guardarUsuario() {
        const usuarioId = document.getElementById('usuarioId').value;
        const nombreUsuario = document.getElementById('nombreUsuario').value;
        const tipoUsuario = document.getElementById('tipoUsuario').value;

        if (nombreUsuario.length > 55) {
            Swal.fire({
                title: 'Error',
                text: 'El nombre no puede tener más de 55 caracteres',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (tipoUsuario === "") {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar un tipo de usuario',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        const metodo = usuarioId ? 'POST' : 'POST';
        let api = usuarioId 
            ? `<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=save'; ?>`
            : "<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=save'; ?>";

        let dataJson = JSON.stringify({ usuarioId: usuarioId, nombreUsuario: nombreUsuario, tipo: tipoUsuario });

        fetch(api, {
            method: metodo,
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
            .then(response => response.json())
            .then((res) => {
                let data = res.data;
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
                $('#usuarioModal').modal('hide');
                cargarUsuarios();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al guardar el usuario',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
    }

    function editarUsuario(idUsuario) {
        let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=get&idUsuario='; ?>${idUsuario}`;

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let usuario = res.data ?? [];
                document.getElementById('usuarioId').value = usuario.idUsuario;
                document.getElementById('nombreUsuario').value = usuario.nombreUsuario;
                document.getElementById('tipoUsuario').value = usuario.tipo;
                document.getElementById('usuarioModalLabel').innerText = 'Editar Usuario';
                $('#usuarioModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    function eliminarUsuario(idUsuario) {
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
                let api = `<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=delete&idUsuario='; ?>${idUsuario}`;

                fetch(api, { method: 'DELETE' })
                    .then(response => response.json())
                    .then((res) => {
                        if (res.status) {
                        Swal.fire(
                            'Eliminado',
                            res.message || 'El usuario ha sido eliminado', // Mensaje desde el backend
                            'success'
                        );
                        cargarUsuarios();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: res.message || 'No se pudo eliminar el usuario', // Mensaje desde el backend
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Hubo un error al eliminar el usuario',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
            }
        });
    }

    function limpiarFormulario() {
        document.getElementById('usuarioId').value = '';
        document.getElementById('nombreUsuario').value = '';
        document.getElementById('tipoUsuario').value = '';
    }
</script>
