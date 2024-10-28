<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Equivalencias hotel, habitación, semanas</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Calculo</a>
                    <span class="breadcrumb-item active">Equivalencias</span>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Equivalencias</h4>
                <form id="formulario-competencia">
                    <div class="form-group">
                        <label for="hotelId">Seleccionar Hotel</label>
                        <select class="form-control" id="hotelId" required>
                            <option value="">Seleccione un hotel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipoHabitacionId">Tipo de Habitación</label>
                        <select class="form-control" id="tipoHabitacionId" required>
                            <option value="">Seleccione un tipo de habitación</option>
                        </select>
                    </div>

                    <!-- Botón para abrir el modal de temporadas y el dropdown -->
                    <div class="form-group row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-info w-100" data-toggle="modal" data-target="#modalTemporadas">
                                Ver Temporadas Disponibles
                            </button>
                        </div>
                        <div class="col-md-6">
                            <div class="dropdown w-100">
                                <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownTemporadas" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ver Temporadas y Colores
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownTemporadas">
                                    <h6 class="dropdown-header">Temporadas:</h6>
                                    <div class="dropdown-item d-flex align-items-center">
                                        <div class="color-box premium"></div> <span class="ml-2">Premium</span>
                                    </div>
                                    <div class="dropdown-item d-flex align-items-center">
                                        <div class="color-box preferred"></div> <span class="ml-2">Preferred</span>
                                    </div>
                                    <div class="dropdown-item d-flex align-items-center">
                                        <div class="color-box select"></div> <span class="ml-2">Select</span>
                                    </div>
                                    <div class="dropdown-item d-flex align-items-center">
                                        <div class="color-box choice"></div> <span class="ml-2">Choice</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fechasId">Seleccionar Fechas (7 noches por semana)</label>
                        <input type="text" class="form-control" id="fechasId" placeholder="Seleccione un rango de semanas">
                    </div>

                    <!-- Contenedor para mostrar el calendario de fechas seleccionadas -->
                    <div hidden id="calendar-selection" class="form-group mt-3 p-3 border rounded" style="display: none;">
                        <h5>Fechas Seleccionadas:</h5>
                        <p id="selected-dates" class="badge badge-info p-2"></p>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="calcularSemanas()">Mostrar estadías base</button>

                    <!-- Contenedor para mostrar la temporada -->
                    <div id="temporada-container" class="mt-3 p-3 border rounded" style="display: none;">
                        <h5>Temporada Correspondiente:</h5>
                        <p id="temporada-text" class="badge badge-warning p-2"></p>
                    </div>

                    <!-- Contenedor para las semanas necesarias -->
                    <div id="semanas-container" class="mt-3 p-3 border rounded" style="display: none;">
                        <h5>Estadías Base Necesarias:</h5>
                        <p id="semanas-text" class="badge badge-success p-2"></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar las temporadas -->
<div class="modal fade" id="modalTemporadas" tabindex="-1" role="dialog" aria-labelledby="modalTemporadasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTemporadasLabel">Temporadas Disponibles</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tipo de Temporada</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Fin</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-temporadas-body">
                        <!-- Filas de la tabla se generarán dinámicamente -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="assets/js/app/data-equivalencias-hotel.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Variable para almacenar las fechas disponibles según el hotel seleccionado
    let fechasDisponibles = [];

    document.addEventListener("DOMContentLoaded", function() {
        cargarHoteles();
        inicializarCalendario();

        document.getElementById("hotelId").addEventListener("change", cargarHabitaciones);
        document.getElementById("tipoHabitacionId").addEventListener("change", calcularSemanas);
    });

    function inicializarCalendario() {
        flatpickr("#fechasId", {
            mode: "range",
            dateFormat: "Y-m-d",
            minDate: "today", // Inicia desde la fecha actual
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                // Resaltamos las fechas según la temporada
                fechasDisponibles.forEach(rango => {
                    const fechaInicio = new Date(rango.from);
                    const fechaFin = new Date(rango.to);
                    const fechaActual = new Date(dayElem.dateObj);

                    // Verificar si el día actual está dentro de alguno de los rangos de temporada
                    if (fechaActual >= fechaInicio && fechaActual <= fechaFin) {
                        // Aplicar clase de color basada en el tipo de temporada
                        dayElem.classList.add('highlight-' + rango.tipo.toLowerCase()); // Añadimos la clase basada en la temporada
                        dayElem.setAttribute('title', `Temporada: ${rango.tipo}`); // Añadimos un tooltip con el tipo de temporada
                    }
                });
            },
            onClose: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const diffDays = Math.floor((selectedDates[1] - selectedDates[0]) / (1000 * 60 * 60 * 24)) + 1;

                    // Validar que sea múltiplo de 8 (una o más semanas de 8 días)
                    if (diffDays % 8 !== 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Fechas inválidas',
                            text: 'Debe seleccionar un rango de semanas completas de 8 días.',
                        });
                        this.clear(); // Reiniciar el calendario si no es válido
                    } else {
                        mostrarFechasSeleccionadas(selectedDates);
                    }
                }
            }
        });
    }

    function mostrarFechasSeleccionadas(selectedDates) {
        if (selectedDates.length > 0) {
            const fechaInicio = selectedDates[0].toLocaleDateString("es-ES");
            const fechaFin = selectedDates[1] ? selectedDates[1].toLocaleDateString("es-ES") : "Selecciona una fecha de fin";

            const fechasSeleccionadas = `Desde: ${fechaInicio} - Hasta: ${fechaFin}`;
            document.getElementById("selected-dates").textContent = fechasSeleccionadas;
            document.getElementById("calendar-selection").style.display = "block";
        }
    }

    function cargarHoteles() {
        const selectHotel = document.getElementById("hotelId");
        document.getElementById("temporada-container").style.display = "block";
        document.getElementById("semanas-container").style.display = "block";
        data.hoteles.forEach(hotel => {
            const option = document.createElement("option");
            option.value = hotel.id;
            option.text = hotel.nombre;
            selectHotel.appendChild(option);
        });
    }

    function cargarHabitaciones() {
        const hotelId = document.getElementById("hotelId").value;
        const selectHabitacion = document.getElementById("tipoHabitacionId");
        const contentTemporadaContainer = document.getElementById("temporada-container");
        const contentSemanasContainer = document.getElementById("semanas-container");
        contentTemporadaContainer.style.display = "none";
        contentSemanasContainer.style.display = "none";

        selectHabitacion.innerHTML = '<option value="">Seleccione un tipo de habitación</option>';

        if (hotelId) {
            const hotel = data.hoteles.find(h => h.id == hotelId);
            hotel.habitaciones.forEach(habitacion => {
                const option = document.createElement("option");
                option.value = habitacion.id;
                option.text = habitacion.tipo;
                selectHabitacion.appendChild(option);
            });

            cargarTablaTemporadas(hotel);
            fechasDisponibles = obtenerFechasTemporadas(hotel); // Cargar las fechas disponibles del hotel
        }
    }

    function cargarTablaTemporadas(hotel) {
        const tablaBody = document.getElementById("tabla-temporadas-body");
        const fechaActual = new Date();
        tablaBody.innerHTML = ""; // Limpiar la tabla antes de llenarla

        hotel.temporadas.forEach(temporada => {
            temporada.semanas.forEach(semana => {
                const fechaFin = new Date(semana.rango.fin);

                // Solo mostrar las temporadas cuya fecha de fin sea mayor o igual a la fecha actual
                if (fechaFin >= fechaActual) {
                    const fila = document.createElement("tr");

                    const tipoTemporada = document.createElement("td");
                    tipoTemporada.textContent = semana.tipo;
                    fila.appendChild(tipoTemporada);

                    const fechaInicio = document.createElement("td");
                    fechaInicio.textContent = formatearFecha(new Date(semana.rango.inicio));
                    fila.appendChild(fechaInicio);

                    const fechaFinFormatted = document.createElement("td");
                    fechaFinFormatted.textContent = formatearFecha(fechaFin);
                    fila.appendChild(fechaFinFormatted);

                    tablaBody.appendChild(fila);
                }
            });
        });
    }

    function obtenerFechasTemporadas(hotel) {
        const fechas = [];
        hotel.temporadas.forEach(temporada => {
            temporada.semanas.forEach(semana => {
                fechas.push({
                    from: semana.rango.inicio,
                    to: semana.rango.fin,
                    tipo: semana.tipo
                });
            });
        });
        return fechas;
    }

    // Función para formatear las fechas en "día de la semana, día/mes/año"
    function formatearFecha(fecha) {
        const opciones = {
            weekday: 'long',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        };
        return fecha.toLocaleDateString('es-ES', opciones);
    }

    function calcularSemanas() {
        const hotelId = document.getElementById("hotelId").value;
        const habitacionId = document.getElementById("tipoHabitacionId").value;
        const fechasSeleccionadas = document.getElementById("fechasId").value;

        if (hotelId && habitacionId && fechasSeleccionadas) {
            const hotel = data.hoteles.find(h => h.id == hotelId);
            const habitacion = hotel.habitaciones.find(h => h.id == habitacionId);
            const temporadaTipo = determinarTemporada(fechasSeleccionadas, hotel);

            const semanas = habitacion.semanas[temporadaTipo] || 0;
            const rangoFechas = fechasSeleccionadas.split(" to "); // Flatpickr usa "to" para separar el rango
            const noches = Math.floor((new Date(rangoFechas[1]) - new Date(rangoFechas[0])) / (1000 * 60 * 60 * 24)) + 1;

            if (!isNaN(noches)) {
                const estadiasBase = semanas * (noches / 7); // Cálculo en base a semanas completas
                const estadiasRedondeadas = Math.ceil(estadiasBase); // Redondeo hacia arriba

                document.getElementById("semanas-text").textContent = estadiasRedondeadas + " Estadías base";

                document.getElementById("semanas-container").style.display = "block";
            } else {
                document.getElementById("semanas-text").textContent = "No se pudo calcular las estadías base.";
            }

            // Mostrar la temporada
            const temporadaBadge = document.getElementById("temporada-text");
            temporadaBadge.textContent = temporadaTipo;
            document.getElementById("temporada-container").style.display = "block";

            // Remover clases anteriores y añadir la clase correspondiente según la temporada
            temporadaBadge.classList.remove('badge-premium', 'badge-select', 'badge-preferred', 'badge-choice'); // Remover clases anteriores

            switch (temporadaTipo.toLowerCase()) {
                case 'premium':
                    temporadaBadge.classList.add('badge-premium');
                    break;
                case 'select':
                    temporadaBadge.classList.add('badge-select');
                    break;
                case 'preferred':
                    temporadaBadge.classList.add('badge-preferred');
                    break;
                case 'choice':
                    temporadaBadge.classList.add('badge-choice');
                    break;
                default:
                    temporadaBadge.classList.add('badge-secondary'); // Clase predeterminada si no hay coincidencias
                    break;
            }
        }
    }

    function determinarTemporada(fechas, hotel) {
        const fechasSeleccionadas = fechas.split(" to "); // Flatpickr separa el rango de fechas con "to"

        if (fechasSeleccionadas.length > 1) {
            const fechaInicio = new Date(fechasSeleccionadas[0]);
            const fechaFin = new Date(fechasSeleccionadas[1]);

            for (const temporada of hotel.temporadas) {
                for (const semana of temporada.semanas) {
                    const fechaInicioTemporada = new Date(semana.rango.inicio);
                    const fechaFinTemporada = new Date(semana.rango.fin);

                    if (
                        (fechaInicio >= fechaInicioTemporada && fechaInicio <= fechaFinTemporada) ||
                        (fechaFin >= fechaInicioTemporada && fechaFin <= fechaFinTemporada)
                    ) {
                        return semana.tipo; // Retornar el tipo de temporada
                    }
                }
            }
        }
        return "Premium"; // Default si no encuentra coincidencias
    }
</script>

<!-- Estilos personalizados para resaltar las temporadas -->
<style>
    /* Colores personalizados según las temporadas */
    .highlight-premium {
        background-color: #9C4428;
        color: white;
    }

    .highlight-preferred {
        background-color: #387A75;
        color: white;
    }

    .highlight-select {
        background-color: #47575C;
        color: white;
    }

    .highlight-choice {
        background-color: #74B8C1;
        color: white;
    }

    .tira-colores {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .color-box {
        width: 30px;
        height: 30px;
        display: inline-block;
        border-radius: 4px;
    }

    .premium {
        background-color: #9C4428;
    }

    .preferred {
        background-color: #387A75;
    }

    .select {
        background-color: #47575C;
    }

    .choice {
        background-color: #74B8C1;
    }
    /* Estilos personalizados para el badge de temporada */
    .badge-premium {
        background-color: #9C4428;
        color: white;
    }

    .badge-preferred {
        background-color: #387A75;
        color: white;
    }

    .badge-select {
        background-color: #47575C;
        color: white;
    }

    .badge-choice {
        background-color: #74B8C1;
        color: white;
    }
</style>