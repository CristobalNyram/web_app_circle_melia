<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Asociación de Usuarios con Equipos y Equipos con Competencias</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Asociaciones</a>
                    <span class="breadcrumb-item active">Usuarios, Equipos y Competencias</span>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Asociar Usuarios a Equipos</h4>
                <form id="formulario-usuario-equipo">
                    <div class="form-group">
                        <label for="usuarioId">Seleccionar Usuario</label>
                        <select class="form-control" id="usuarioId" required></select>
                    </div>
                    <div class="form-group">
                        <label for="equipoId">Seleccionar Equipo</label>
                        <select class="form-control" id="equipoId" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="asociarUsuarioEquipo()">Asociar Usuario a Equipo</button>
                </form>

                <button class="btn btn-secondary mt-3" data-toggle="modal" data-target="#asociacionesUsuarioEquipoModal" onclick="cargarAsociacionesUsuarioEquipo()">Ver Asociaciones Usuarios-Equipos</button>

                <hr>

                <h4>Asociar Equipos a Competencias</h4>
                <form id="formulario-equipo-competencia">
                    <div class="form-group">
                        <label for="equipoIdComp">Seleccionar Equipo</label>
                        <select class="form-control" id="equipoIdComp" required></select>
                    </div>
                    <div class="form-group">
                        <label for="competenciaId">Seleccionar Competencia</label>
                        <select class="form-control" id="competenciaId" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="asociarEquipoCompetencia()">Asociar Equipo a Competencia</button>
                </form>

                <button class="btn btn-secondary mt-3" data-toggle="modal" data-target="#asociacionesEquipoCompetenciaModal" onclick="cargarAsociacionesEquipoCompetencia()">Ver Asociaciones Equipos-Competencias</button>
            </div>
        </div>
    </div>

    <!-- Modal para asociaciones de usuarios con equipos -->
    <div class="modal fade" id="asociacionesUsuarioEquipoModal" tabindex="-1" role="dialog" aria-labelledby="asociacionesUsuarioEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asociacionesUsuarioEquipoModalLabel">Asociaciones de Usuarios con Equipos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabla-usuario-equipo" class="table">
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>ID Usuario</th>
                                <th>Nombre Usuario</th>
                                <th>Nombre Equipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenido dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para asociaciones de equipos con competencias -->
    <div class="modal fade" id="asociacionesEquipoCompetenciaModal" tabindex="-1" role="dialog" aria-labelledby="asociacionesEquipoCompetenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="asociacionesEquipoCompetenciaModalLabel">Asociaciones de Equipos con Competencias</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="tabla-equipo-competencia" class="table">
                        <thead>
                            <tr>
                                <th>Folio</th>

                                <th>Nombre Equipo</th>
                                <th>Nombre Competencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenido dinámico -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar asociación usuario-equipo -->
    <div class="modal fade" id="editarUsuarioEquipoModal" tabindex="-1" role="dialog" aria-labelledby="editarUsuarioEquipoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarUsuarioEquipoModalLabel">Editar Asociación de Usuario con Equipo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar-usuario-equipo">
                        <input type="hidden" id="editarUsuarioIdFolio">
                        <input type="hidden" id="editarUsuarioId">
                        <input type="hidden" id="editarEquipoId">
                        <div class="form-group">
                            <label for="nuevoUsuarioId">Nuevo Usuario</label>
                            <select class="form-control" id="nuevoUsuarioId" required></select>
                        </div>
                        <div class="form-group">
                            <label for="nuevoEquipoId">Nuevo Equipo</label>
                            <select class="form-control" id="nuevoEquipoId" required></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCambiosUsuarioEquipo()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar asociación equipo-competencia -->
    <div class="modal fade" id="editarEquipoCompetenciaModal" tabindex="-1" role="dialog" aria-labelledby="editarEquipoCompetenciaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarEquipoCompetenciaModalLabel">Editar Asociación de Equipo con Competencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar-equipo-competencia">
                       <input type="hidden" id="editarEquipoCompIdFolio">

                        <input type="hidden" id="editarEquipoCompId">
                        <input type="hidden" id="editarCompetenciaId">
                        <div class="form-group">
                            <label for="nuevoEquipoCompId">Nuevo Equipo</label>
                            <select class="form-control" id="nuevoEquipoCompId" required></select>
                        </div>
                        <div class="form-group">
                            <label for="nuevoCompetenciaId">Nueva Competencia</label>
                            <select class="form-control" id="nuevoCompetenciaId" required></select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCambiosEquipoCompetencia()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript functionality for associations -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        cargarUsuarios();
        cargarEquipos();
        cargarCompetencias();
    });

    function cargarUsuarios() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/users/?action=list'; ?>";
        fetch(api)
            .then(response => response.json())
            .then(res => {
                // console.log(res);
                let usuariosCrudo = res.data ?? [];
                let usuarios = usuariosCrudo.filter(usuario => usuario.tipo === 'vendedor');

                const usuarioSelect = document.getElementById('usuarioId');
                const nuevoUsuarioSelect = document.getElementById('nuevoUsuarioId');
                usuarios.forEach(usuario => {
                    let option = document.createElement('option');
                    option.value = usuario.idUsuario;
                    option.text = usuario.nombreUsuario;
                    usuarioSelect.add(option);
                    nuevoUsuarioSelect.add(option.cloneNode(true));
                });
            });
    }

    function cargarEquipos() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/equipos/?action=list'; ?>";
        fetch(api)
            .then(response => response.json())
            .then(res => {
                let equipos = res.data ?? [];
                const equipoSelect1 = document.getElementById('equipoId');
                const equipoSelect2 = document.getElementById('equipoIdComp');
                const nuevoEquipoSelect = document.getElementById('nuevoEquipoId');
                const nuevoEquipoCompSelect = document.getElementById('nuevoEquipoCompId');
                equipos.forEach(equipo => {
                    let option = document.createElement('option');
                    option.value = equipo.idEquipo;
                    option.text = equipo.nombreEquipo;
                    equipoSelect1.add(option);
                    equipoSelect2.add(option.cloneNode(true));
                    nuevoEquipoSelect.add(option.cloneNode(true));
                    nuevoEquipoCompSelect.add(option.cloneNode(true));
                });
            });
    }

    function cargarCompetencias() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/competencias/?action=list'; ?>";
        fetch(api)
            .then(response => response.json())
            .then(res => {
                let competencias = res.data ?? [];
                const competenciaSelect = document.getElementById('competenciaId');
                const nuevoCompetenciaSelect = document.getElementById('nuevoCompetenciaId');
                competencias.forEach(competencia => {
                    let option = document.createElement('option');
                    option.value = competencia.idCompetencia;
                    option.text = competencia.nombreCompetencia;
                    competenciaSelect.add(option);
                    nuevoCompetenciaSelect.add(option.cloneNode(true));
                });
            });
    }

    function asociarUsuarioEquipo() {
        const usuarioId = document.getElementById('usuarioId').value;
        const equipoId = document.getElementById('equipoId').value;

        if (!usuarioId || !equipoId) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar un usuario y un equipo',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=asociarUsuarioEquipo'; ?>";
        let dataJson = JSON.stringify({ usuarioId: usuarioId, equipoId: equipoId });

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
        })
        .catch(error => console.error('Error:', error));
    }

    function asociarEquipoCompetencia() {
        const equipoId = document.getElementById('equipoIdComp').value;
        const competenciaId = document.getElementById('competenciaId').value;

        if (!equipoId || !competenciaId) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar un equipo y una competencia',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=asociarEquipoCompetencia'; ?>";
        let dataJson = JSON.stringify({ equipoId: equipoId, competenciaId: competenciaId });

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
        })
        .catch(error => console.error('Error:', error));
    }

    function cargarAsociacionesUsuarioEquipo() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=listAsociacionesUsuarioEquipo'; ?>";

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let asociaciones = res.data ?? [];
                const tableBody = document.querySelector('#tabla-usuario-equipo tbody');
                tableBody.innerHTML = '';
                asociaciones.forEach(asociacion => {
                    const row = `<tr>
                        <td>${asociacion.id}</td>
                        <td>${asociacion.idUsuario}</td>
                        <td>${asociacion.nombreUsuario}</td>
                        <td>${asociacion.nombreEquipo}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarAsociacionUsuarioEquipo(${asociacion.id},${asociacion.idUsuario}, ${asociacion.idEquipo})">Editar</button>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            });
    }

    function cargarAsociacionesEquipoCompetencia() {
        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=listAsociacionesEquipoCompetencia'; ?>";

        fetch(api)
            .then(response => response.json())
            .then(res => {
                let asociaciones = res.data ?? [];
                const tableBody = document.querySelector('#tabla-equipo-competencia tbody');
                tableBody.innerHTML = '';
                asociaciones.forEach(asociacion => {
                    const row = `<tr>
                        <td>${asociacion.id}</td>
                        <td>${asociacion.nombreEquipo}</td>
                        <td>${asociacion.nombreCompetencia}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarAsociacionEquipoCompetencia(${asociacion.id},${asociacion.idEquipo}, ${asociacion.idCompetencia})">Editar</button>
                        </td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            });
    }

    function editarAsociacionUsuarioEquipo(folio,idUsuario, idEquipo) {
        document.getElementById('editarUsuarioIdFolio').value = folio;
        document.getElementById('editarUsuarioId').value = idUsuario;
        document.getElementById('editarEquipoId').value = idEquipo;
        $('#editarUsuarioEquipoModal').modal('show');
    }

    function guardarCambiosUsuarioEquipo() {
        const folio = document.getElementById('editarUsuarioIdFolio').value;
        const usuarioId = document.getElementById('editarUsuarioId').value;
        const equipoId = document.getElementById('editarEquipoId').value;
        const nuevoUsuarioId = document.getElementById('nuevoUsuarioId').value;
        const nuevoEquipoId = document.getElementById('nuevoEquipoId').value;

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=editarUsuarioEquipo'; ?>";
        let dataJson = JSON.stringify({ folio: folio, usuarioId: usuarioId, equipoId: equipoId, nuevoUsuarioId: nuevoUsuarioId, nuevoEquipoId: nuevoEquipoId });
        console.log(folio);
        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            console.log(res);
            if (res.status) {
                Swal.fire({
                    title: 'Éxito',
                    text: res.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                $('#editarUsuarioEquipoModal').modal('hide');
                cargarAsociacionesUsuarioEquipo();
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

    function editarAsociacionEquipoCompetencia(folio,idEquipo, idCompetencia) {
        // console.log(idEquipo);
        document.getElementById('editarEquipoCompIdFolio').value = folio;
        document.getElementById('editarEquipoCompId').value = idEquipo;
        document.getElementById('editarCompetenciaId').value = idCompetencia;
        $('#editarEquipoCompetenciaModal').modal('show');
    }

    function guardarCambiosEquipoCompetencia() {
        const folio = document.getElementById('editarEquipoCompIdFolio').value;
        const equipoId = document.getElementById('editarEquipoCompId').value;
        const competenciaId = document.getElementById('editarCompetenciaId').value;
        const nuevoEquipoId = document.getElementById('nuevoEquipoCompId').value;
        const nuevoCompetenciaId = document.getElementById('nuevoCompetenciaId').value;

        let api = "<?php echo BASE_URL_PROJECT.'app/api/v1/asociaciones/?action=editarEquipoCompetencia'; ?>";
        let dataJson = JSON.stringify({ folio: folio, equipoId: equipoId, competenciaId: competenciaId, nuevoEquipoId: nuevoEquipoId, nuevoCompetenciaId: nuevoCompetenciaId });
        console.log(folio);
        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: dataJson
        })
        .then(response => response.json())
        .then(res => {
            console.log(res);
            if (res.status) {
                Swal.fire({
                    title: 'Éxito',
                    text: res.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
                $('#editarEquipoCompetenciaModal').modal('hide');
                cargarAsociacionesEquipoCompetencia();
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
