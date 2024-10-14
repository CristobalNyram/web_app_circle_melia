<!DOCTYPE html>
<html lang="es">
<head>
    <?php
    // Define a global variable ROOT_PATH if not already defined
    if (!defined('ROOT_PATH_ASSETS')) {
        define('ROOT_PATH_ASSETS', '../'); // Default path if ROOT_PATH is not defined
    }
    ?>

    <base href="<?php echo ROOT_PATH_ASSETS; ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login de Equipos</title>

    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <link href="assets/css/app.min.css" rel="stylesheet">
</head>

<body>
    <div class="app">
        <div class="container-fluid p-h-0 p-v-20 bg full-height d-flex">
            <div class="d-flex flex-column justify-content-between w-100">
                <div class="container d-flex h-100">
                    <div class="row align-items-center w-100">
                        <div class="col-md-7 col-lg-5 m-h-auto">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between m-b-30">
                                        <h2 class="m-b-0">Login de Equipos</h2>
                                    </div>
                                    <form id="loginEquipoForm">
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="equipoSelect">Seleccionar Equipo:</label>
                                            <select class="form-control" id="equipoSelect" required>
                                                <option value="">Selecciona un equipo</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-semibold" for="password">Contraseña del equipo:</label>
                                            <div class="input-affix">
                                                <i class="prefix-icon anticon anticon-lock"></i>
                                                <input type="password" class="form-control" id="password" placeholder="Contraseña del equipo" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                                        </div>
                                    </form>
                                    <div id="errorMessage" class="text-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-md-flex p-h-40 justify-content-between">
                    <span class="">© 2024 Melia</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Vendors JS -->
    <script src="assets/js/vendors.min.js"></script>
    <script src="assets/js/app.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarEquipos(); // Cargar los equipos cuando la página carga
        });

        function cargarEquipos() {
            fetch('<?php echo BASE_URL_PROJECT; ?>app/api/v1/equipos/?action=list')
                .then(response => response.json())
                .then(res => {
                    let equipos = res.data ?? [];
                    const equipoSelect = document.getElementById('equipoSelect');
                    equipos.forEach(equipo => {
                        let option = document.createElement('option');
                        option.value = equipo.idEquipo;
                        option.text = equipo.nombreEquipo;
                        equipoSelect.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        document.getElementById('loginEquipoForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const equipoId = document.getElementById('equipoSelect').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');

            fetch('<?php echo BASE_URL_PROJECT; ?>app/api/v1/auth/?action=loginEquipo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ equipoId: equipoId, contrasenia: password })
            })
            .then(response => response.json())
            .then(res => {
                if (res.status) {
                    window.location.href = '<?php echo BASE_URL_PROJECT; ?>pages/ventas-equipo'; // Redireccionar a la página del equipo
                } else {
                    errorMessage.textContent = res.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = 'Error en la conexión. Intente de nuevo.';
            });
        });
    </script>
</body>
</html>
