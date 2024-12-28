<style>
    /* Estilo principal del input con el ID específico */
    #inputDiferenciaAhorro {
        background-color: #f5f5f5;
        /* Fondo suave */
        border: 2px solid #ddd;
        /* Borde inicial */
        border-radius: 10px;
        /* Bordes redondeados */
        padding: 10px 15px;
        /* Espaciado interno */
        font-size: 1.5rem;
        /* Tamaño grande del texto */
        font-family: 'Arial', sans-serif;
        /* Fuente */
        color: #333;
        /* Color del texto */
        font-weight: bold;
        /* Negrita */
        text-align: center;
        /* Centrar el texto */
        outline: none;
        /* Quitar borde al enfoque */
        transition: all 0.3s ease-in-out;
        /* Transiciones suaves */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Sombra ligera */
    }

    /* Efecto al pasar el mouse */
    #inputDiferenciaAhorro:hover {
        background-color: #ffffff;
        /* Cambiar fondo al pasar el mouse */
        border-color: #007bff;
        /* Cambiar borde */
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
        /* Incrementar sombra */
    }

    /* Efecto al hacer clic o estar enfocado */
    #inputDiferenciaAhorro:focus {
        background-color: #e9f5ff;
        /* Fondo sutil para foco */
        border-color: #0056b3;
        /* Borde más oscuro */
        box-shadow: 0 4px 10px rgba(0, 86, 179, 0.3);
        /* Sombra pronunciada */
        color: #0056b3;
        /* Cambiar color del texto */
    }

    /* Input readonly */
    #inputDiferenciaAhorro[readonly] {
        background-color: #f0f0f0;
        /* Fondo diferente para readonly */
        color: #555;
        /* Texto ligeramente más oscuro */
        cursor: not-allowed;
        /* Indicar que no es editable */
    }

    /* Animación de palpitación */
    @keyframes palpitar {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    /* Aplicar la animación */
    #inputDiferenciaAhorro {
        animation: palpitar 3.5s infinite ease-in-out;
        /* Latido continuo */
    }

    /* diferencia ahorro fin */

    .animate-title {
        animation: pulse 1s ease-in-out;
    }

    /* Ocultar el checkbox */
    .form-check-input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    /* Estilos personalizados para el label (botón) */
    .form-check-label {
        display: inline-block;
        padding: 10px 20px;
        font-size: 1rem;
        text-align: center;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    /* Estilo del botón cuando está seleccionado */
    #checkboxAhorro:checked+.form-check-label {
        background-color: #0d6efd;
        /* Azul (similar a btn-primary) */
        color: white;
        border: 2px solid #0056b3;
        /* Bordes más oscuros */
    }

    #checkboxCalidad:checked+.form-check-label {
        background-color: #198754;
        /* Verde (similar a btn-success) */
        color: white;
        border: 2px solid #146c43;
        /* Bordes más oscuros */
    }

    /* Estilo del botón cuando no está seleccionado */
    .form-check-label.btn {
        background-color: #e9ecef;
        /* Gris claro */
        color: #6c757d;
        /* Texto gris */
        border: 1px solid #ced4da;
    }


    @keyframes pulse {
        0% {
            transform: scale(1);
            color: #007bff;
            /* Cambia a un color llamativo */
        }

        50% {
            transform: scale(1.1);
            color: #0056b3;
        }

        100% {
            transform: scale(1);
            color: #007bff;
        }
    }

    .page-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
    }

    .progress-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
        /* Distribuir espacio equitativo entre pasos */
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #dcdcdc;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: #6c757d;
        /* Color del número */
        position: relative;
        z-index: 1;
        /* Asegurar que el número esté sobre el fondo */
        overflow: hidden;
        /* Ocultar contenido del pseudo-elemento fuera del círculo */
    }

    .step-circle::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #007bff;
        /* Color del llenado */
        z-index: -1;
        /* Mantener el fondo detrás del número */
        transition: top 1.6s ease-in-out;
        /* Transición para el llenado */
    }

    .progress-step.active .step-circle::after {
        top: 0;
        /* Llenar el círculo completamente */
    }

    .progress-step.active .step-circle {
        color: #fff;
        /* Cambiar el color del número a blanco al activarse */
    }

    .progress-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background-color: #dcdcdc;
        z-index: -1;
        /* Mantener la línea debajo de los círculos */
        transition: background-color 0.6s ease-in-out;
        /* Transición para el color de la línea */
    }

    .progress-step.active~.progress-line {
        background-color: #007bff;
        /* Cambiar el color de la línea cuando un paso está activo */
    }


    .step-circle::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: transparent;
        /* Color inicial transparente */
        z-index: -1;
        /* Mantener el fondo detrás del número */
        transition: top 1.6s ease-in-out, background-color 0.6s ease-in-out;
        /* Transición para el llenado y color */
    }

    /* Colores específicos por paso */
    .progress-step[data-step="1"].active .step-circle::after {
        background-color: #007bff;
        /* Azul */
    }

    .progress-step[data-step="2"].active .step-circle::after {
        background-color: #dc3545;
        /* Rojo */
    }

    .progress-step[data-step="3"].active .step-circle::after {
        background-color: #28a745;
        /* Verde */
    }

    .progress-step[data-step="4"].active .step-circle::after {
        background-color: #ffc107;
        /* Amarillo (opcional para más pasos) */
    }

    .progress-step.active .step-circle::after {
        top: 0;
        /* Llenar el círculo completamente */
    }

    .progress-step.active .step-circle {
        color: #fff;
        /* Cambiar el color del número a blanco al activarse */
    }

    /* card steps */
    .card-form-steps {
        border: 1px solid #dee2e6;
        /* Color suave para el borde */
        border-radius: 16px;
        /* Bordes ligeramente redondeados */
        background-color: #ffffff;
        /* Fondo blanco */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
        /* Sombra sutil */
        padding: 1.5rem;
        /* Espaciado interno más amplio */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Transiciones suaves para efectos */
    }

    .card-form-steps:hover {
        transform: translateY(-5px);
        /* Elevar ligeramente al pasar el cursor */
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15), 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Sombra más pronunciada */
    }

    .card-form-steps.card-title {
        font-size: 1.25rem;
        /* Tamaño de fuente mayor para el título */
        font-weight: bold;
        color: #343a40;
        /* Color de texto oscuro */
        margin-bottom: 1rem;
        /* Separación del contenido */
        text-align: center;
        /* Centrar el título */
    }

    .card-form-steps.card-content {
        font-size: 1rem;
        /* Fuente legible para el contenido */
        color: #6c757d;
        /* Color de texto suave */
        line-height: 1.5;
        /* Mejorar la legibilidad */
        text-align: center;
        /* Centrar el contenido */
    }

    .card-form-steps .card-actions {
        margin-top: 1.5rem;
        /* Separación de las acciones */
        display: flex;
        justify-content: center;
        /* Centrar los botones */
        gap: 0.5rem;
        /* Espaciado entre botones */
    }

    .card-form-steps .btn {
        border-radius: 24px;
        /* Bordes redondeados en los botones */
        font-weight: bold;
        transition: background-color 0.3s ease, transform 0.3s ease;
        /* Transiciones para hover */
    }

    .card-form-steps .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .card-form-steps .btn-primary:hover {
        background-color: #0056b3;
        /* Azul más oscuro */
        transform: scale(1.05);
        /* Ligeramente más grande */
    }

    .card-form-steps .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .card-form-steps .btn-secondary:hover {
        background-color: #495057;
        /* Gris más oscuro */
        transform: scale(1.05);
    }

    /* alert  */

    .custom-alert {
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        padding: 15px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        transition: all 0.3s ease-in-out;
        margin-top: 10px;
    }

    .custom-alert.alert-success {
        background-color: #d4edda;
        /* Verde claro */
        color: #155724;
        /* Texto verde oscuro */
        border: 1px solid #c3e6cb;
    }

    .custom-alert.alert-danger {
        background-color: #f8d7da;
        /* Rojo claro */
        color: #721c24;
        /* Texto rojo oscuro */
        border: 1px solid #f5c6cb;
    }

    .custom-alert.alert-warning {
        background-color: #fff3cd;
        /* Amarillo claro */
        color: #856404;
        /* Texto amarillo oscuro */
        border: 1px solid #ffeeba;
    }

    .custom-alert.alert-info {
        background-color: #d1ecf1;
        /* Azul claro */
        color: #0c5460;
        /* Texto azul oscuro */
        border: 1px solid #bee5eb;
    }

    .resaltado {
        border: 3px solid #ffc107 !important;
        /* Amarillo para resaltar */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
    }
</style>
<div class="page-container mt-5 ">
    <!-- Barra de progreso -->
    <div class="w-50 mt-5 ">
        <div class="progress-container">
            <div class="progress-step active" data-step="1">
                <div class="step-circle">1</div>
                <small>Paso </small>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step" data-step="2">
                <div class="step-circle">2</div>
                <small>Paso </small>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step" data-step="3">
                <div class="step-circle">3</div>
                <small>Paso </small>
            </div>
            <div class="progress-line"></div>
            <div class="progress-step" data-step="4">
                <div class="step-circle">4</div>
                <small>Paso </small>
            </div>
        </div>
    </div>

    <!-- Tarjeta del formulario -->
    <div class="card card-form-steps w-md-50 w-lg-70 w-80">
        <div class="card-body text-center">
            <h2 id="step-header" class="card-title">Semana Vacacional Tradicional </h2>
            <form id="step-form">
                <div class="step-content" id="step-1">
                    <div class="row">
                        <!-- Selección de Tour Operador -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field0">Tour Operador</label>
                            <select class="form-control" id="field0" name="field0" required>
                                <option value="" disabled selected>Selecciona un operador</option>
                                <option value="expedia">Expedia</option>
                                <option value="booking">Booking.com</option>
                                <option value="airbnb">Airbnb</option>
                                <option value="tripadvisor">TripAdvisor</option>
                                <option value="travelocity">Travelocity</option>
                                <option value="kayak">Kayak</option>
                                <option value="priceline">Priceline</option>
                                <option value="orbitz">Orbitz</option>
                                <option value="hotels">Hotels.com</option>
                                <option value="skyscanner">Skyscanner</option>
                                <option value="agoda">Agoda</option>
                                <option value="trivago">Trivago</option>
                                <option value="hopper">Hopper</option>
                                <option value="otium">Otium</option>
                                <option value="despegar">Despegar.com</option>
                                <option value="otro">Elegir otro</option>
                            </select>
                        </div>

                        <!-- Campo personalizado para Tour Operador -->
                        <div class="col-12 col-md-6 form-group d-none" id="custom-operator-container">
                            <label for="field1">Especificar Otro Tour Operador</label>
                            <input type="text" minlength="3" required maxlength="155" class="form-control" id="field1" name="field1" placeholder="Ingresa otro operador">
                        </div>

                        <!-- Campo para Monto -->


                        <!-- Campo para Monto con Select de País -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field2">País y Monto </label>
                            <div class="input-group">
                                <!-- Select de País -->
                                <div class="input-group-prepend">
                                    <select id="country-select" name="country" class="form-control form-control-sm" style="max-width: 80px;" required>
                                        <option value="México" selected>MX</option>
                                        <option value="Estados Unidos">US</option>
                                        <option value="Canadá">CA</option>
                                        <option value="España">ES</option>
                                        <option value="Chile">CL</option>
                                        <option value="Reino Unido">UK</option>
                                        <option value="Colombia">CO</option>
                                        <option value="Argentina">AR</option>
                                        <option value="Brasil">BR</option>
                                        <option value="Portugal">PT</option>
                                        <option value="República Dominicana">DO</option>
                                    </select>

                                </div>
                                <!-- Campo de Monto -->
                                <input
                                    onchange="validateAmountMontoTourOperador(event)"
                                    oninput="formatCurrency(event);"
                                    onblur='fetchCalculoInflacion(document.getElementById("country-select").value, this.value);'


                                    type="text"
                                    class="form-control"
                                    id="field2"
                                    placeholder="$"
                                    required>
                            </div>
                        </div>


                        <div class="col-12 mt-1">
                            <div id="alert-box-monto-tour-operador" class="alert d-none" role="alert"></div>
                        </div>
                        <!-- Resultados de inflación -->
                        <div id="inflation-results" class="col-12 row d-none"> <!-- Ahora es una fila -->
                            <div class="col-12 col-md-4 form-group">
                                <label for="inflation-2-years">En 2 años serán:</label>
                                <input type="text" id="inflation-2-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-4 form-group">
                                <label for="inflation-5-years">En 5 años serán:</label>
                                <input

                                    type="text" id="inflation-5-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-4 form-group">
                                <label for="inflation-10-years">En 10 años serán:</label>
                                <input type="text" id="inflation-10-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label for="inflation-15-years">En 15 años serán:</label>
                                <input type="text" id="inflation-15-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-6 form-group">
                                <label for="inflation-20-years">En 20 años serán:</label>
                                <input type="text" id="inflation-20-years"
                                    onchange="assignInputValue('inflation-20-years', 'field16');"

                                    class="form-control" readonly>
                            </div>
                        </div>
                        <!-- Resultados de inflación Fin -->


                        <!-- Fechas de inicio y fin -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field3">Fecha de Inicio:</label>
                            <input type="date" id="field3" name="fecha-inicio" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label for="field4">Fecha de Fin:</label>
                            <input type="date" id="field4" name="fecha-fin" class="form-control" required>
                        </div>

                        <!-- Mensaje de error -->
                        <div class="col-12">
                            <div class="alert alert-danger mt-3 d-none" id="date-error-1">
                                El periodo entre las fechas debe ser de <span id="nights"></span> noches.
                            </div>
                        </div>

                        <!-- Campo para Hotel -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field5">Hotel:</label>
                            <input type="text" id="field5" minlength="3" maxlength="155" name="hotel" class="form-control" placeholder="Escribe el nombre del hotel" required>
                        </div>

                        <!-- Campo para Habitación -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field6">Habitación:</label>
                            <input type="text" id="field6" minlength="3" maxlength="155" name="habitacion" class="form-control" placeholder="Escribe el nombre de la habitación" required>
                        </div>

                        <!-- Campo para "Incluye" -->
                        <div class="col-12 form-group">
                            <label for="field7">Incluye:</label>
                            <textarea id="field7" name="incluye" minlength="3" maxlength="155" class="form-control" rows="3" placeholder="Ejemplo: desayuno, almuerzo, spa"></textarea>
                        </div>
                    </div>
                </div>

                <div class="step-content d-none" id="step-2">
                    <div class="row">
                        <!-- Selección de Tour Operador -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field10">Tour Operador</label>
                            <select class="form-control" id="field10" name="field10" required>
                                <option value="circle" selected>Circle By Melia</option>
                            </select>
                        </div>

                        <!-- Monto -->
                        <div class="col-md-6 col-12 form-group">
                            <label for="field11">Monto</label>
                            <input
                                oninput="formatCurrency(event);calculateDifferenceAndPercentage();"
                                type="text"
                                onchange="calculateDifferenceAndPercentage()"

                                onblur='fetchCalculoInflacionConAhorroProyeccion(document.getElementById("country-select").value, this.value);'
                                class="form-control"
                                id="field11"
                                placeholder="$ Ingresa el monto"
                                required>
                        </div>
                        <!-- Resultados de inflación -->
                        <div id="inflation-socio-results" class="col-12 row d-none"> <!-- Ahora es una fila -->

                            <div class="col-12 col-md-3 form-group">
                                <label for="inflation-socio-5-years">En 5 años serán:</label>
                                <input type="text" id="inflation-socio-5-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-3 form-group">
                                <label for="inflation-socio-10-years">En 10 años serán:</label>
                                <input type="text" id="inflation-socio-10-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-3 form-group">
                                <label for="inflation-15-years">En 15 años serán:</label>
                                <input type="text" id="inflation-socio-15-years" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-md-3 form-group">
                                <label for="inflation-socio-20-years">En 20 años serán:</label>
                                <input type="text" id="inflation-socio-20-years" class="form-control" readonly>
                            </div>
                        </div>
                        <!-- Resultados de inflación Fin -->

                        <!-- Fechas de inicio y fin -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field12">Fecha de Inicio:</label>
                            <input type="date" id="field12" name="fecha-inicio" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label for="field13">Fecha de Fin:</label>
                            <input type="date" id="field13" name="fecha-fin" class="form-control" required>
                        </div>

                        <!-- Mensaje de error -->
                        <div class="col-12">
                            <div class="alert alert-danger mt-3 d-none" id="date-error-2">
                                El periodo entre las fechas debe ser de <span id="nights-2"></span> noches.
                            </div>
                        </div>

                        <!-- Campo para Hotel -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field14">Hotel:</label>
                            <input
                                type="text"
                                id="field14"
                                minlength="3"
                                maxlength="155"
                                name="hotel"
                                class="form-control"
                                placeholder="Escribe el nombre del hotel"
                                required>
                        </div>

                        <!-- Campo para Habitación -->
                        <div class="col-12 col-md-6 form-group">
                            <label for="field15">Habitación:</label>
                            <input
                                type="text"
                                id="field15"
                                minlength="3"
                                maxlength="155"
                                name="habitacion"
                                class="form-control"
                                placeholder="Escribe el nombre de la habitación"
                                required>
                        </div>

                        <!-- Campo Semana Tradicional -->
                        <div class="col-12 col-md-5 form-group text-center">
                            <label for="field16" class="font-weight-bold">Semana Tradicional</label>
                            <input
                                type="text"
                                id="field16"
                                name="semana-tradicional"
                                class="form-control text-center"
                                placeholder="$0.00"
                                readonly>
                        </div>

                        <!-- Campo VS (Separador Visual) -->
                        <div class="col-12 col-md-2 text-center my-2 my-md-0">
                            <span class="vs-divider d-inline-block py-2 px-4 bg-light text-muted font-weight-bold rounded">VS</span>
                        </div>

                        <!-- Campo Socio Circle -->
                        <div class="col-12 col-md-5 form-group text-center">
                            <label for="field17" class="font-weight-bold">Socio Circle</label>
                            <input
                                type="text"
                                id="field17"
                                name="socio-circle"
                                class="form-control text-center"
                                placeholder="$0.00"
                                readonly>
                        </div>
                        <!-- Campo El Socio Ahorra -->
                        <div class="form-group col-md-12 col-12">
                            <label for="field18" class="font-weight-bold">El Socio Ahorra:</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <!-- Ahorro en Monto -->
                                <div class="d-flex flex-column align-items-start mr-md-2 mb-2 mb-md-0">
                                    <label for="field18" class="small font-weight-bold">Ahorro en Monto ($):</label>
                                    <input
                                        type="text"
                                        id="field18"
                                        name="socio-ahorra-monto"
                                        class="form-control"
                                        placeholder="Ahorro en monto ($)"
                                        readonly>
                                </div>

                                <!-- Ahorro en Porcentaje -->
                                <div class="d-flex flex-column align-items-start mr-md-2 mb-2 mb-md-0">
                                    <label for="field19" class="small font-weight-bold">Ahorro en Porcentaje (%):</label>
                                    <input
                                        type="text"
                                        id="field19"
                                        name="socio-ahorra-porcentaje"
                                        class="form-control"
                                        placeholder="Ahorro en porcentaje (%)"
                                        readonly>
                                </div>

                                <!-- Selector de Multiplicador -->
                                <div class="d-flex flex-column align-items-start">
                                    <label for="field20" class="small font-weight-bold">Multiplicado por:</label>
                                    <select
                                        id="field20"
                                        name="socio-multiplicador"
                                        class="form-control"
                                        oninput="calculateDifferenceAndPercentage()">
                                        <option value="1" selected>x1</option>
                                        <option value="2">x2</option>
                                        <option value="3">x3</option>
                                        <option value="4">x4</option>
                                        <option value="5">x5</option>
                                        <option value="6">x6</option>
                                        <option value="7">x7</option>
                                        <option value="8">x8</option>
                                        <option value="9">x9</option>
                                        <option value="10">x10</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Campos del Paso 2 fin -->



                <!-- Campos del Paso 3 -->
                <div class="step-content d-none" id="step-3">
                    <div class="row text-center mb-2">
                        <div class="col-12">
                            <h3>Selecciona tu opción</h3>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkboxAhorro" value="ahorro">
                                <label class="form-check-label btn btn-success btn-block" for="checkboxAhorro">Quiero Ahorro</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="checkboxCalidad" value="calidad">
                                <label class="form-check-label btn btn-success btn-block" for="checkboxCalidad">Quiero Calidad</label>
                            </div>
                        </div>
                    </div>
                    <!-- Contenidos dinámicos -->
                    <div id="resultados-montos-comparacion-container" class="row mt-4 ">

                    </div>
                </div>


                <!-- Campos del Paso 4 -->
                <!-- Campos del Paso 4 -->
                <div class="step-content d-none" id="step-4">
                    <div class="row">
                        <!-- Tarjeta de Desglose -->
                        <div class="col-12 mb-3">
                            <div class="card border-info">
                                <div class="card-header text-center bg-info text-white pt-3">
                                    <h5 class="text-white">Desglose de Cálculos</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="inputSemanasResultado" class="form-label">Valor de Semanas:</label>
                                            <input type="text" readonly class="form-control" id="inputSemanasResultado">
                                        </div>
                                        <div class="col-md-4">
                                            <!-- <label for="inputSemanaExperienciaResultado" class="form-label">Semana con Experiencia:</label> -->
                                            <label for="inputSemanaExperienciaResultado" class="form-label">Valor de Experiencia:</label>

                                            <input type="text" readonly class="form-control" id="inputSemanaExperienciaResultado">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="inputSemanaExperienciaResultadoSumaDeAmbos" class="form-label">Suma de semanas:</label>
                                            <input type="text" readonly class="form-control" id="inputSemanaExperienciaResultadoSumaDeAmbos">
                                        </div>
                                     




                                    </div>
                                    <br>
                                       <div class="row ">
                                            <!-- Columna 1: Suma de semanas -->
                                            <div class="col-md-4">
                                                <label for="inputSemanaExperienciaResultadoSumaDeAmbosFinalResult" class="form-label">Valor total de semanas:</label>
                                                <input type="text" readonly class="form-control" id="inputSemanaExperienciaResultadoSumaDeAmbosFinalResult">
                                            </div>

                                            <!-- Columna 2: Total de proyección (con select a un lado del input) -->
                                            <div class="col-md-4 ">
                                                <label for="inputMontoCalculoSemanaResultadoTotalProyeccionAniosProyectar" class="form-label" style="flex: 1;">Años a proyectar:</label>
                                                <input type="text" readonly class="form-control" id="inputMontoCalculoSemanaResultadoTotalProyeccionAniosProyectar" >
                                               
                                            </div>

                                            <!-- Columna 3: Otro campo Total de proyección -->
                                            <div class="col-md-4">
                                                <label for="inputMontoCalculoSemanaResultadoTotalProyeccion" class="form-label">Total de proyeccion:</label>
                                                <input type="text" readonly class="form-control" id="inputMontoCalculoSemanaResultadoTotalProyeccion">
                                            </div>
                                        </div>
                                </div>



                            </div>
                        </div>

                        <!-- Primera Esfera -->
                        <div class="col-12 col-md-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-header text-center bg-primary text-white pt-3">
                                    <h5 class="text-white">Cálculo de Semanas</h5>
                                </div>
                                <div class="card-body">
                                    
        <!--  -->
    <div class="row ">
        <div class="col-md-6">
            <label for="inputMontoCalculoSemanaResultado" class="form-label">Cálculo de semanas:</label>
            <input type="text" readonly class="form-control" id="inputMontoCalculoSemanaResultado">
        </div>
        <div class="col-md-4">
            <label for="selectDivisor" class="form-label">Dividir por:</label>
            <br>
            <select class="form-select" id="selectDivisor" onchange="actualizarValoresSumaSemanasTotales()">
                <!-- Opciones del select -->
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
            </select>
        </div>
    </div>

        <!--  -->

                                    <div class="mb-3">
                                        <label for="inputMontoTourOperadorResultado" class="form-label">Monto Tour Operador:</label>
                                        <input type="text" readonly class="form-control" id="inputMontoTourOperadorResultado">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputTotalTourOperadorResultado" class="form-label">Total:</label>
                                        <input type="text" readonly class="form-control" id="inputTotalTourOperadorResultado">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Segunda Esfera -->
                        <div class="col-12 col-md-6 mb-3">
                            <div class="card border-success">
                                <div class="card-header text-center bg-success text-white pt-3">
                                    <h5 class="text-white">Membresía y Resultados</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="inputValorMembresiaResultado" class="form-label">Valor membresía:</label>
                                        <input type="text" readonly class="form-control" id="inputValorMembresiaResultado">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputValorOperarConMeliaResultado" class="form-label">Operar con Melia:</label>
                                        <input type="text" readonly class="form-control" id="inputValorOperarConMeliaResultado">
                                    </div>
                                    <div class="mb-3">
                                        <label for="inputTotalOperarConMeliaResultado" class="form-label">Total:</label>
                                        <input type="text" readonly class="form-control" id="inputTotalOperarConMeliaResultado">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Diferencia y Ahorro -->
                        <div class="col-12 mb-3">
                            <div class="card border-warning">
                                <div class="card-header text-center bg-warning text-white pt-3">
                                    <h5 class="text-white">Diferencia y Ahorro</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <!-- <label for="inputDiferenciaAhorro" class="form-label">Diferencia y Ahorro:</label> -->
                                        <input type="text" readonly class="form-control" id="inputDiferenciaAhorro">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Botones de navegación -->
                <div class="row">
                    <div class="col-6 text-left">
                        <button type="button" class="btn btn-secondary prev-step d-none">Anterior</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-primary next-step">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>