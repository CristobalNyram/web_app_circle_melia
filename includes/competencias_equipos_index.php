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
        <div class="row d-flex justify-content-center align-content-center mb-1">
            <img src="assets/images/resources/logoMembersFest.png" alt="Logo" class="logo-header mr-3" style="width: 200px; height: auto;">
        </div>

        <div class="card">
            <div class="card-body">
                <h4 hidden>Seleccionar Competencia</h4>
                <form id="formulario-competencia">
                    <div class="form-group">
                        <label for="competenciaId">Seleccionar Competencia</label>
                        <select class="form-control" id="competenciaId" required></select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="cargarVentasCompetencia()">Mostrar Ventas</button>
                </form>
                <button hidden type="button" class="btn btn-success mt-2" id="downloadTotalVentasEquipos">Descargar Total por Equipos (Excel)</button> <!-- Botón para descargar el total de ventas de equipos -->
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
                <h5 class="modal-title" id="modalDetalleVentasLabel">Detalle de Ventas del Vendedores</h5>
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
                        </tr>
                    </thead>
                    <tbody id="detalleVentasBody">
                        <!-- Contenido dinámico -->
                    </tbody>
                    <tfoot hidden>
                        <tr>
                            <th colspan="3" class="text-right">Total de Ventas Activas:</th>
                            <th id="totalVentasActivas">0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="downloadDetalleVentasVendedor">Descargar Detalle de vendedores (Excel)</button> <!-- Botón para descargar el detalle del vendedor -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Gráfica de Ventas -->
<div class="modal fade" id="modalGraficaVentas" tabindex="-1" role="dialog" aria-labelledby="modalGraficaVentasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGraficaVentasLabel">Gráfica de Ventas por Vendedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="graficaVentas" width="400" height="200"></canvas> <!-- Contenedor de la gráfica -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="downloadChart">Descargar Gráfica (PNG)</button> <!-- Botón para descargar la gráfica -->
                <button type="button" class="btn btn-success" id="downloadTotalVendedores">Descargar Total por Vendedores (Excel)</button> <!-- Botón para descargar el total por vendedores -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Importar Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script> <!-- Importar SheetJS -->

<script>
    let ventasAgrupadas = []; // Declarar globalmente para que sea accesible
    let chart; // Variable para almacenar la gráfica de ventas

    document.addEventListener('DOMContentLoaded', function() {
        cargarCompetencias(); // Cargar las competencias disponibles en el select
    });

    function cargarCompetencias() {
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/competencias/?action=list'; ?>";
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
                title: 'Aviso',
                text: 'Debe seleccionar una competencia',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=listVentasCompetencia'; ?>";
        let dataJson = JSON.stringify({
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
                    mostrarMetaVentas(res.data.competencia);
                    mostrarVentasEnCards(res.data.equipos);
                } else {
                    Swal.fire({
                        title: 'Aviso',
                        text: res.message,
                        icon: 'warning',
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
                <h3>${competencia.nombreCompetencia}</h3>
                <h3 hidden>Meta de Ventas para ${competencia.nombreCompetencia}: $ ${Number(competencia.metaVentas).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</h3>
            </div>
        `;
    }

    function mostrarVentasEnCards(equipos) {
        const cardsContainer = document.getElementById('cards-container');
        cardsContainer.innerHTML = ''; // Limpiar contenido previo
        equipos.forEach(equipo => {
            const metasAlcanzadas = Math.floor(equipo.ventasAcumuladas / equipo.metaVentas);
            const progresoFinal = (equipo.ventasAcumuladas % equipo.metaVentas) / equipo.metaVentas * 100;
            let barrasProgreso = '';

            const obtenerClaseProgreso = (porcentaje) => {
                if (porcentaje < 50) {
                    return 'bg-danger';
                } else if (porcentaje >= 50 && porcentaje < 80) {
                    return 'bg-warning';
                } else {
                    return 'bg-success';
                }
            };

            for (let i = 0; i < metasAlcanzadas; i++) {
                barrasProgreso += `
                <div class="progress mb-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>`;
            }

            if (progresoFinal > 0 || metasAlcanzadas === 0) {
                const progressBarClass = obtenerClaseProgreso(progresoFinal);
                barrasProgreso += `
                <div class="progress mb-2">
                    <div class="progress-bar ${progressBarClass}" role="progressbar" style="width: ${progresoFinal}%;" aria-valuenow="${progresoFinal}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>`;
            }

            const mensajeMetas = metasAlcanzadas > 0 ? `<small hidden class="text-success">¡Meta alcanzada ${metasAlcanzadas} ${metasAlcanzadas > 1 ? 'veces' : 'vez'}!</small>` : '';

            const card = `
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Equipo: ${equipo.nombreEquipo}</h5>
                            <p class="card-text">Ventas Totales: $${Number(equipo.ventasAcumuladas).toLocaleString('es-MX', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</p>
                            ${barrasProgreso}
                            ${mensajeMetas}
                            <div class="mt-auto text-end">
                                <button class="btn btn-info" onclick="verDetalleVentas(${equipo.idEquipo})">Ver Detalle de Ventas</button>
                                <button class="btn btn-secondary" onclick="verGraficaVentas(${equipo.idEquipo})">Ver Gráfica</button> <!-- Botón de la gráfica -->
                            </div>
                        </div>
                    </div>
                </div>`;

            cardsContainer.insertAdjacentHTML('beforeend', card);
        });
    }

    function verDetalleVentas(equipoId) {
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=detalleVentas'; ?>";
        let dataJson = JSON.stringify({
            equipoId: equipoId,
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
                    // Actualizamos la variable global ventasAgrupadas
                    ventasAgrupadas = agruparVentasPorVendedor(res.data);
                    mostrarVentasAgrupadas(ventasAgrupadas);
                    $('#modalDetalleVentas').modal('show');
                } else {
                    Swal.fire({
                        title: 'Aviso',
                        text: res.message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function agruparVentasPorVendedor(ventas) {
        let ventasAgrupadas = {};

        // Filtrar solo las ventas con estado "Activo" (estado 2)
        ventas.filter(venta => venta.estado === 2).forEach(venta => {
            if (!ventasAgrupadas[venta.nombreVendedor]) {
                ventasAgrupadas[venta.nombreVendedor] = {
                    nombreVendedor: venta.nombreVendedor,
                    totalMonto: 0,
                    detalleVentas: []
                };
            }

            // Sumar el monto de ventas activas
            ventasAgrupadas[venta.nombreVendedor].totalMonto += parseFloat(venta.monto);
            ventasAgrupadas[venta.nombreVendedor].detalleVentas.push(venta);
        });

        return Object.values(ventasAgrupadas);
    }

    function mostrarVentasAgrupadas(ventasAgrupadas) {
        const detalleBody = document.getElementById('detalleVentasBody');
        detalleBody.innerHTML = ''; 

        ventasAgrupadas.forEach(vendedor => {
            const row = `
                <tr>
                    <td>${vendedor.nombreVendedor}</td>
                    <td>${Number(vendedor.totalMonto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td><button class="btn btn-info" onclick="mostrarDetalleVentasVendedor('${vendedor.nombreVendedor}')">Ver Detalles</button></td>
                </tr>`;
            detalleBody.insertAdjacentHTML('beforeend', row);
        });
    }

    function mostrarDetalleVentasVendedor(nombreVendedor) {
        const detalleBody = document.getElementById('detalleVentasBody');
        detalleBody.innerHTML = ''; 

        let vendedor = ventasAgrupadas.find(v => v.nombreVendedor === nombreVendedor);
        let totalVentasActivas = 0;

        vendedor.detalleVentas.forEach(venta => {
            let badgeColor = '';
            let badgeText = '';

            switch (venta.estado) {
                case 1:
                    badgeColor = 'badge-warning';
                    badgeText = 'Stand by';
                    break;
                case 2:
                    badgeColor = 'badge-success';
                    badgeText = 'Activo';
                    totalVentasActivas += parseFloat(venta.monto); // Sumar solo las ventas activas
                    break;
                case -2:
                    badgeColor = 'badge-danger';
                    badgeText = 'Cancelado';
                    break;
                default:
                    badgeColor = 'badge-secondary';
                    badgeText = 'Desconocido';
                    break;
            }

            const row = `
                <tr>
                    <td>${venta.nombreVendedor}</td>
                    <td>${Number(venta.monto).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    <td><span class="badge ${badgeColor}">${badgeText}</span></td>
                    <td>${venta.fecha}</td>
                </tr>`;
            detalleBody.insertAdjacentHTML('beforeend', row);
        });

        // Mostrar el total de ventas activas en el pie de la tabla
        document.getElementById('totalVentasActivas').textContent = `$${totalVentasActivas.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        $('#modalDetalleVentas').modal('show'); // Asegurar que se muestre el modal
    }

    function verGraficaVentas(equipoId) {
        ventasAgrupadas =[];
        if (ventasAgrupadas.length === 0) {
            // Si no hay ventas agrupadas, cargar los datos antes de generar la gráfica
            let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=detalleVentas'; ?>";
            let dataJson = JSON.stringify({
                equipoId: equipoId,
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
                        ventasAgrupadas = agruparVentasPorVendedor(res.data); // Agrupar ventas
                        generarGrafica(); // Generar la gráfica con los datos cargados
                    } else {
                        Swal.fire({
                            title: 'Aviso',
                            text: res.message,
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            generarGrafica(); // Si ya hay datos cargados, generar la gráfica directamente
        }
    }

    function generarGrafica() {
        const nombresVendedores = ventasAgrupadas.map(v => v.nombreVendedor);
        const montosVentas = ventasAgrupadas.map(v => v.totalMonto);

        // Destruir la gráfica anterior si existe
        if (chart) {
            chart.destroy();
        }

        const ctx = document.getElementById('graficaVentas').getContext('2d');
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: nombresVendedores,
                datasets: [{
                    label: 'Ventas Totales Activas',
                    data: montosVentas,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        $('#modalGraficaVentas').modal('show'); // Mostrar modal de la gráfica
    }

    // Función para descargar la gráfica
    document.getElementById('downloadChart').addEventListener('click', function() {
        const link = document.createElement('a');
        link.href = chart.toBase64Image();
        link.download = 'grafica_ventas.png';
        link.click();
    });

    // Función para descargar el total de ventas por equipos en formato Excel
    document.getElementById('downloadTotalVentasEquipos').addEventListener('click', function() {
        let equiposData = ventasAgrupadas.map(v => ({
            Vendedor: v.nombreVendedor,
            'Ventas Totales': v.totalMonto
        }));

        let ws = XLSX.utils.json_to_sheet(equiposData);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Ventas por Equipos');
        XLSX.writeFile(wb, 'total_ventas_equipos.xlsx');
    });

    // Función para descargar el total de ventas por vendedores en formato Excel
    document.getElementById('downloadTotalVendedores').addEventListener('click', function() {
        let vendedoresData = ventasAgrupadas.map(v => ({
            Vendedor: v.nombreVendedor,
            'Ventas Totales': v.totalMonto
        }));

        let ws = XLSX.utils.json_to_sheet(vendedoresData);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Ventas por Vendedores');
        XLSX.writeFile(wb, 'total_ventas_vendedores.xlsx');
    });

    // Función para descargar el detalle del vendedor en formato Excel
    document.getElementById('downloadDetalleVentasVendedor').addEventListener('click', function() {
        let detalleData = [];

        ventasAgrupadas.forEach(vendedor => {
            vendedor.detalleVentas.forEach(venta => {
                detalleData.push({
                    Vendedor: vendedor.nombreVendedor,
                    Monto: venta.monto,
                    Estado: venta.estado === 2 ? 'Activo' : venta.estado === 1 ? 'Stand by' : 'Cancelado',
                    Fecha: venta.fecha
                });
            });
        });

        let ws = XLSX.utils.json_to_sheet(detalleData);
        let wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Detalle de Ventas por Vendedor');
        XLSX.writeFile(wb, 'detalle_ventas_vendedor.xlsx');
    });
</script>
