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
                                <th>Nickname</th>
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
                            <select class="form-control" id="tipoUsuario"  onchange="toggleFieldsTypeUser()" required>

                                <option value="">Seleccionar tipo</option>
                                <option value="admin">Administrador</option>
                                <option value="vendedor">Vendedor</option>
                            </select>
                        </div>
                        <div class="form-group" id="nicknameGroup" style="display:none;">
                            <label for="nickname">Nickname</label>
                            <input type="text" class="form-control" id="nickname" maxlength="50" required>
                        </div>
                        <div class="form-group" id="contraseniaGroup" style="display:none;">
                            <label for="contrasenia">Contraseña</label>
                            <input type="text" class="form-control" id="contrasenia" maxlength="100" required>
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
    function toggleFieldsTypeUser() {
        console.log('2');
        const tipoUsuario = document.getElementById('tipoUsuario').value;
        const nicknameGroup = document.getElementById('nicknameGroup');
        const contraseniaGroup = document.getElementById('contraseniaGroup');
        const nickname = document.getElementById('nickname');
        const contrasenia = document.getElementById('contrasenia');
        
        if (tipoUsuario === 'vendedor') {
            // Ocultar y limpiar campos, y quitar el atributo 'required'
            nicknameGroup.style.display = 'none';
            contraseniaGroup.style.display = 'none';
            nickname.value = '';
            contrasenia.value = '';
            nickname.removeAttribute('required');
            contrasenia.removeAttribute('required');
        } else {
            // Mostrar campos y agregar el atributo 'required'
            nicknameGroup.style.display = 'block';
            contraseniaGroup.style.display = 'block';
            nickname.setAttribute('required', 'required');
            contrasenia.setAttribute('required', 'required');
        }
    }

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
                        <td>${usuario.nickname ?? ''}</td>
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
        const nickname = document.getElementById('nickname').value;
        const contrasenia = document.getElementById('contrasenia').value;
        const tipoUsuario = document.getElementById('tipoUsuario').value;

        if (nombreUsuario.length > 55) {
            Swal.fire({
                title: 'Aviso',
                text: 'El nombre no puede tener más de 55 caracteres',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (tipoUsuario === "") {
            Swal.fire({
                title: 'Aviso',
                text: 'Debe seleccionar un tipo de usuario',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const metodo = usuarioId ? 'POST' : 'POST';
        let api = usuarioId 
            ? `<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=save'; ?>`
            : "<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=save'; ?>";

        let dataJson = JSON.stringify({ 
            usuarioId: usuarioId, 
            nombreUsuario: nombreUsuario, 
            nickname: nickname, 
            contrasenia: contrasenia,
            tipo: tipoUsuario 
        });

        fetch(api, {
            method: metodo,
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
            .then(response => response.json())
            .then((res) => {
                let data = res.data;
                console.log(res);
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
                $('#usuarioModal').modal('hide');
                cargarUsuarios();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Aviso',
                    text: 'Hubo un error al guardar el usuario',
                    icon: 'warning',
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
                console.log(res);
                document.getElementById('usuarioId').value = usuario.idUsuario;
                document.getElementById('nombreUsuario').value = usuario.nombreUsuario;
                document.getElementById('nickname').value = usuario.nickname;
                document.getElementById('contrasenia').value = usuario.contrasenia;
                document.getElementById('tipoUsuario').value = usuario.tipo;
                toggleFieldsTypeUser();
                document.getElementById('usuarioModalLabel').innerText = 'Editar Usuario';
                $('#usuarioModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }

    function limpiarFormulario() {
        document.getElementById('usuarioId').value = '';
        document.getElementById('nombreUsuario').value = '';
        document.getElementById('nickname').value = '';
        document.getElementById('contrasenia').value = '';
        document.getElementById('tipoUsuario').value = '';
    }
</script>
