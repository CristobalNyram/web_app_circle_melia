<div class="page-container">

    <!-- Content Wrapper START -->
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
    <!-- Content Wrapper END -->

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
                                <th>ID Usuario</th>
                                <th>Nombre Usuario</th>
                                <th>Nombre Equipo</th>
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
                                <th>Nombre Equipo</th>
                                <th>Nombre Competencia</th>
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
                let usuarios = res.data ?? [];
                const usuarioSelect = document.getElementById('usuarioId');
                usuarios.forEach(usuario => {
                    let option = document.createElement('option');
                    option.value = usuario.idUsuario;
                    option.text = usuario.nombreUsuario;
                    usuarioSelect.add(option);
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
                equipos.forEach(equipo => {
                    let option = document.createElement('option');
                    option.value = equipo.idEquipo;
                    option.text = equipo.nombreEquipo;
                    equipoSelect1.add(option);
                    equipoSelect2.add(option);
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
                competencias.forEach(competencia => {
                    let option = document.createElement('option');
                    option.value = competencia.idCompetencia;
                    option.text = competencia.nombreCompetencia;
                    competenciaSelect.add(option);
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
                        <td>${asociacion.idUsuario}</td>
                        <td>${asociacion.nombreUsuario}</td>
                        <td>${asociacion.nombreEquipo}</td>
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
                        <td>${asociacion.nombreEquipo}</td>
                        <td>${asociacion.nombreCompetencia}</td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            });
    }
</script>
