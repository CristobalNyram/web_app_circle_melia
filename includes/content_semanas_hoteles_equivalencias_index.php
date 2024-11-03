<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /*
    --------------------------------------->FIN 
    Colores personalizados según las temporadas
    --------------------------------------->FIN 
    */
    .highlight-premium {
        background-color: #9C4428;
        color: white;
    }

    .highlight-preferred {
        background-color: #387A75;
        color: white;
    }

    .highlight-select {
        background-color: black;
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
        background-color: #be5829;
    }

    .preferred {
        background-color: #387A75;
    }

    .select {
        background-color: black;
    }

    .choice {
        background-color: #74B8C1;
    }

    /* Estilos personalizados para el badge de temporada */
    .badge-premium {
        background-color: #be5829;
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

    /*
    --------------------------------------->FIN 
    Colores personalizados según las temporadas
    --------------------------------------->FIN 
    */
</style>
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
            <div class="card-body text-center">
                <h5>Selecciona la opción de equivalencias que deseas visualizar:</h5>
                <label>
                    <input type="radio" name="cardSelection" value="membresia" onclick="mostrarCardSeleccionado('membresia')" checked> Equivalencias de Membresía
                </label>

                <label class="mr-3">
                    <input type="radio" name="cardSelection" value="hotel" onclick="mostrarCardSeleccionado('hotel')" > Equivalencias por Hoteles
                </label>
                <label class="mr-3">
                    <input type="radio" name="cardSelection" value="semana" onclick="mostrarCardSeleccionado('semana')"> Equivalencias por Semana
                </label>

            </div>
        </div>


        <div class="card" id="card-hotel" style="display: none;">
            <div class="card-body">
                <h4>
                    <i class="anticon anticon-home"></i>
                    Equivalencias por Hotel
                </h4>
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
        <div class="card" id="card-semana" style="display: none;">
            <div class="card-body">
                <h4>
                    <i class="anticon anticon-home"></i>
                    Equivalencias por Semana
                </h4>
                <form id="formulario-equivalencia-semana">
                    <!-- Campo de selección de rango de semanas -->
                    <div class="form-group">
                        <div class="form-row align-items-end">
                            <div class="col-md-6 col-lg-4 ">
                                <label for="equivalenciaSemanasId">Seleccionar Semanas</label>
                                <input type="number" class="form-control" id="equivalenciaSemanasId" placeholder="Ingrese el número de semanas" required>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <button type="button" class="btn btn-primary w-100" onclick="mostrarBusquedaConcidenciasPorSemanas()">Mostrar búsqueda</button>
                            </div>
                        </div>
                    </div>



                    <!-- Botón para ejecutar la búsqueda -->
                </form>

                <!-- Contenedor para mostrar los resultados en tarjeta -->
                <div id="resultado-container-equivalencia-semana" class="mt-4" style="display: block;">


                </div>
            </div>
        </div>
        <div class="card" id="card-membresia" >
            <div class="card-body">
                <h4>
                    <i class="anticon anticon-idcard"></i>
                    Equivalencias Semanas por Membresía
                </h4>
                <form id="membership-form">

                    <div class="form-group form-row">
                        <div class="col-md-4">
                            <label for="membershipType">Tipo de Membresía</label>
                            <select class="form-control" id="membershipType" required>
                                <option value="">Seleccione un tipo de membresía</option>
                                <option value="INFINITE BLUE">INFINITE BLUE</option>
                                <option value="INFINITE RED">INFINITE RED</option>
                                <option value="INFINITE BLACK">INFINITE BLACK</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="mountInvesmentMembershipType">Mostrar margen de inversión así a</label>
                            <select class="form-control" id="mountInvesmentMembershipType" required>
                                <option value="">Seleccione uno</option>
                                <option value="Arriba">Mayor a</option>
                                <option value="Abajo">Menor a</option>
                                <option value="Ambos">Ambos</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="investmentAmount">Monto Invertido</label>
                            <input type="number" class="form-control" id="investmentAmount" placeholder="Ingrese el monto invertido" required>
                        </div>

                    </div>


                    <button type="submit" class="btn btn-primary">Calcular Semanas</button>
                </form>

                <div id="result" class="mt-4" style="display: none;">
                    <h4>Resultado</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Inversión</th>
                                <th>Semanas Totales</th>
                                <th>Distribución por Año</th>
                                <th hidden>Beneficios</th>
                                <th hidden>Intercambio de Semanas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="investment"></td>
                                <td id="totalWeeks"></td>
                                <td>
                                    <ul id="yearlyDistribution"></ul>
                                </td>
                                <td hidden id="bonus"></td>
                                <td hidden id="exchange"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Acordeón para mostrar opciones de inversión cercanas -->
                <div class="accordion mt-4" id="investmentOptions" style="display: none;">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Opción de Inversión Más Baja
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#investmentOptions">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Inversión</th>
                                            <th>Semanas Totales</th>
                                            <th>Distribución por Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="lowerInvestment"></td>
                                            <td id="lowerWeeks"></td>
                                            <td>
                                                <ul id="lowerDistribution"></ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Opción de Inversión Más Alta
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#investmentOptions">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Inversión</th>
                                            <th>Semanas Totales</th>
                                            <th>Distribución por Año</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td id="higherInvestment"></td>
                                            <td id="higherWeeks"></td>
                                            <td>
                                                <ul id="higherDistribution"></ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

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
    function mostrarCardSeleccionado(cardId) {
        // Ocultar todas las tarjetas
        document.getElementById("card-hotel").style.display = "none";
        document.getElementById("card-semana").style.display = "none";
        document.getElementById("card-membresia").style.display = "none";

        // Mostrar solo la tarjeta seleccionada
        if (cardId === "hotel") {
            document.getElementById("card-hotel").style.display = "block";
        } else if (cardId === "semana") {
            document.getElementById("card-semana").style.display = "block";
        } else if (cardId === "membresia") {
            document.getElementById("card-membresia").style.display = "block";
        }
    }
</script>
<script>
    //-------------------------------------------->INICIO
    // SCRIPTS PARA BUSCAR POR SEMANAS
    //-------------------------------------------->INICIO
    // Array para obtener el nombre del mes
    const nombresMeses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    // Función para convertir formato de fecha de yyyy-mm-dd a dd-mm-yyyy
    function formatearFechaSemanas(fecha) {
        const [year, month, day] = fecha.split("-");
        return `${day}-${month}-${year}`;
    }

    function mostrarBusquedaConcidenciasPorSemanas() {
        const semanas = parseInt(document.getElementById("equivalenciaSemanasId").value, 10);
        const resultadosContainer = document.getElementById("resultado-container-equivalencia-semana");

        resultadosContainer.innerHTML = ""; // Limpiar los resultados anteriores

        if (!isNaN(semanas) && semanas > 0) {
            data.hoteles.forEach(hotel => {
                hotel.habitaciones.forEach(habitacion => {
                    const temporadasCoincidentes = [];
                    Object.keys(habitacion.semanas).forEach(tipoTemporada => {
                        if (habitacion.semanas[tipoTemporada] === semanas) {
                            const fechasTemporada = hotel.temporadas.map(temporada =>
                                temporada.semanas
                                .filter(semana => semana.tipo === tipoTemporada)
                                .map(semana => ({
                                    inicio: formatearFechaSemanas(semana.rango.inicio),
                                    fin: formatearFechaSemanas(semana.rango.fin)
                                }))
                            ).flat();

                            temporadasCoincidentes.push({
                                tipo: tipoTemporada,
                                fechas: fechasTemporada
                            });
                        }
                    });

                    // Si hay temporadas coincidentes, generar las tarjetas
                    if (temporadasCoincidentes.length > 0) {
                        const card = document.createElement("div");
                        card.classList.add("card", "m-2", "border", "shadow-sm");
                        card.style.borderColor = "#007bff"; // Añadir borde azul para resaltar
                        card.style.boxShadow = "0 4px 8px rgba(0, 0, 0, 0.2)"; // Añadir sombra
                        card.style.marginBottom = "20px"; // Añadir espacio entre tarjetas

                        const cardBody = document.createElement("div");
                        cardBody.classList.add("card-body");

                        const hotelName = document.createElement("h5");
                        hotelName.classList.add("card-title");
                        hotelName.textContent = `Hotel: ${hotel.nombre}`;
                        hotelName.style.color = "#007bff"; // Añadir color para resaltar

                        const roomType = document.createElement("p");
                        roomType.classList.add("card-text");
                        roomType.textContent = `Tipo de Habitación: ${habitacion.tipo}`;

                        // Agregar temporadas y fechas en formato de lista, agrupadas por mes
                        temporadasCoincidentes.forEach(temporada => {
                            const temporadaInfo = document.createElement("div");
                            temporadaInfo.classList.add("card-text", "mt-3");
                            temporadaInfo.innerHTML = `<strong>Temporada:</strong> ${temporada.tipo}`;

                            const fechasAgrupadas = {}; // Objeto para agrupar fechas por mes y año
                            temporada.fechas.forEach(fecha => {
                                const [day, month, year] = fecha.inicio.split("-");
                                const mesAnio = `${nombresMeses[parseInt(month, 10) - 1]} ${year}`; // Obtener nombre del mes y año

                                if (!fechasAgrupadas[mesAnio]) {
                                    fechasAgrupadas[mesAnio] = [];
                                }
                                fechasAgrupadas[mesAnio].push(`${fecha.inicio} al ${fecha.fin}`);
                            });

                            const fechasList = document.createElement("ul");
                            fechasList.style.paddingLeft = "20px";
                            fechasList.style.listStyleType = "circle";

                            // Recorrer cada mes y mostrar sus fechas
                            Object.keys(fechasAgrupadas).forEach(mesAnio => {
                                const mesHeader = document.createElement("li");
                                mesHeader.innerHTML = `<strong>${mesAnio}</strong>`;
                                fechasList.appendChild(mesHeader);

                                const mesFechas = document.createElement("ul");
                                mesFechas.style.paddingLeft = "20px";
                                fechasAgrupadas[mesAnio].forEach(fecha => {
                                    const fechaItem = document.createElement("li");
                                    fechaItem.textContent = fecha;
                                    mesFechas.appendChild(fechaItem);
                                });
                                fechasList.appendChild(mesFechas);
                            });

                            temporadaInfo.appendChild(fechasList);
                            cardBody.appendChild(temporadaInfo);
                        });

                        cardBody.appendChild(hotelName);
                        cardBody.appendChild(roomType);
                        card.appendChild(cardBody);
                        resultadosContainer.appendChild(card);
                    }
                });
            });

            if (resultadosContainer.innerHTML === "") {
                resultadosContainer.innerHTML = "<p>No se encontraron coincidencias para el número de semanas especificado.</p>";
            }
        } else {
            resultadosContainer.innerHTML = "<p>Por favor, ingrese un número válido de semanas.</p>";
        }
    }

    //-------------------------------------------->FIN
    // SCRIPTS PARA BUSCAR POR SEMANAS
    //-------------------------------------------->FIN
</script>

<script>
    //-------------------------------------------->INICIO
    // SCRIPTS PARA TEMPORADAS MEMBRESIAS
    //-------------------------------------------->INICIO
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

    //-------------------------------------------->FIN
    // SCRIPTS PARA TEMPORADAS MEMBRESIAS
    //-------------------------------------------->FIN
</script>

<script>
    //-------------------------------------------->INICIO
    // SCRIPTS PARA MEMBRESIAS INICIO
    //-------------------------------------------->INICIO

    async function fetchMemberships() {
        try {
            const response = await fetch('assets/js/app/data-membresias.json'); // Ruta proporcionada
            if (!response.ok) {
                throw new Error('Error al cargar el archivo JSON');
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error al cargar los datos de membresías:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar la información de membresías. Por favor, inténtalo más tarde.',
            });
        }
    }
    document.getElementById("membership-form").addEventListener("submit", async function(event) {
        event.preventDefault();

        // Limpiar resultados anteriores
        document.getElementById("result").style.display = "none";
        document.getElementById("yearlyDistribution").innerHTML = "";
        document.getElementById("investmentOptions").style.display = "none";
        document.getElementById("collapseOne").style.display = "none"; // Ocultar por defecto
        document.getElementById("collapseTwo").style.display = "none"; // Ocultar por defecto

        const membershipType = document.getElementById("membershipType").value;
        const investmentAmount = parseFloat(document.getElementById("investmentAmount").value);
        const mountInvesmentMembershipType = document.getElementById("mountInvesmentMembershipType").value;

        if (!membershipType || isNaN(investmentAmount) || investmentAmount <= 0 || !mountInvesmentMembershipType) {
            Swal.fire({
                icon: 'warning',
                title: 'Datos incompletos',
                text: 'Por favor, selecciona un tipo de membresía, el margen de inversión y un monto válido.',
            });
            return;
        }

        const memberships = await fetchMemberships();
        const membershipList = memberships[membershipType];
        const margin = 500;

        let filteredMemberships = [];

        // Filtrar según la opción seleccionada en mountInvesmentMembershipType
        if (mountInvesmentMembershipType === "Arriba") {
            filteredMemberships = membershipList.filter(m => m.investment >= investmentAmount);
        } else if (mountInvesmentMembershipType === "Abajo") {
            filteredMemberships = membershipList.filter(m => m.investment <= investmentAmount);
        } else if (mountInvesmentMembershipType === "Ambos") {
            filteredMemberships = membershipList;
        }

        const membership = filteredMemberships.find(m => investmentAmount >= m.investment - margin && investmentAmount <= m.investment + margin);

        if (membership) {
            document.getElementById("investment").textContent = `$${membership.investment.toFixed(2)}`;
            document.getElementById("totalWeeks").textContent = membership.weeksTotal;

            membership.weeksPerYear.forEach((weeks, index) => {
                const li = document.createElement("li");
                li.textContent = `Año ${index + 1}: ${weeks} Semanas base`;
                document.getElementById("yearlyDistribution").appendChild(li);
            });

            document.getElementById("bonus").textContent = membership.bonus || "N/A";
            document.getElementById("exchange").textContent = membership.exchange || "N/A";
            document.getElementById("result").style.display = "block";
        } else {
            let lowerMembership = null;
            let higherMembership = null;

            filteredMemberships.forEach(m => {
                if (m.investment < investmentAmount) {
                    if (!lowerMembership || Math.abs(investmentAmount - m.investment) < Math.abs(investmentAmount - lowerMembership.investment)) {
                        lowerMembership = m;
                    }
                } else if (m.investment > investmentAmount) {
                    if (!higherMembership || Math.abs(investmentAmount - m.investment) < Math.abs(investmentAmount - higherMembership.investment)) {
                        higherMembership = m;
                    }
                }
            });

            if (lowerMembership || higherMembership) {
                // Mostrar solo el recuadro correspondiente
                if (lowerMembership && mountInvesmentMembershipType !== "Arriba") {
                    document.getElementById("lowerInvestment").textContent = `$${lowerMembership.investment.toFixed(2)}`;
                    document.getElementById("lowerWeeks").textContent = lowerMembership.weeksTotal;
                    document.getElementById("lowerDistribution").innerHTML = "";
                    lowerMembership.weeksPerYear.forEach((weeks, index) => {
                        const li = document.createElement("li");
                        li.textContent = `Año ${index + 1}: ${weeks} Semanas base`;
                        document.getElementById("lowerDistribution").appendChild(li);
                    });
                    document.getElementById("collapseOne").style.display = "block";
                }

                if (higherMembership && mountInvesmentMembershipType !== "Abajo") {
                    document.getElementById("higherInvestment").textContent = `$${higherMembership.investment.toFixed(2)}`;
                    document.getElementById("higherWeeks").textContent = higherMembership.weeksTotal;
                    document.getElementById("higherDistribution").innerHTML = "";
                    higherMembership.weeksPerYear.forEach((weeks, index) => {
                        const li = document.createElement("li");
                        li.textContent = `Año ${index + 1}: ${weeks} Semanas base`;
                        document.getElementById("higherDistribution").appendChild(li);
                    });
                    document.getElementById("collapseTwo").style.display = "block";
                }

                document.getElementById("investmentOptions").style.display = "block";

                Swal.fire({
                    icon: 'warning',
                    title: 'No se encontró coincidencia exacta',
                    text: 'Se muestran las opciones más cercanas de inversión.',
                });
            } else {
                // Si no se encuentran opciones cercanas, mostrar un mensaje y ocultar los recuadros
                Swal.fire({
                    icon: 'error',
                    title: 'Sin coincidencias',
                    text: 'No se encontraron opciones de inversión cercanas.',
                });
                document.getElementById("collapseOne").style.display = "none";
                document.getElementById("collapseTwo").style.display = "none";
            }
        }
    });
    //-------------------------------------------->FIN
    // SCRIPTS PARA MEMBRESIAS INICIO
    //-------------------------------------------->FIN
</script>