<script src="assets/js/vendors.min.js"></script>
<script src="assets/vendors/chartjs/Chart.min.js"></script>
<script src="assets/js/app.min.js"></script>
<script src="assets/js/app/data-equivalencias-hotel-general.js"></script>
<script src="assets/js/app/data-membresias.json"></script>

<script>
    // Helpers inicio
    function formatCurrency(e) {
        // Obtener el valor actual del input
        let value = e.target.value;

        // Eliminar cualquier carácter no numérico, excepto el punto
        value = value.replace(/[^0-9.]/g, '');

        // Prevenir más de un punto decimal
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts[1];
        }

        // Limitar a dos decimales
        if (parts[1]?.length > 2) {
            value = parts[0] + '.' + parts[1].substring(0, 2);
        }

        // Formatear con comas (solo parte entera)
        const [integer, decimal] = value.split('.');
        const formattedInteger = integer.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

        // Reconstruir el valor formateado
        e.target.value = decimal !== undefined ? `${formattedInteger}.${decimal}` : formattedInteger;
    }

    function validateDatePeriod(startDateId, endDateId, requiredNights, errorMessageSelector) {
        // Obtener valores de las fechas
        const startDate = new Date(document.getElementById(startDateId).value);
        const endDate = new Date(document.getElementById(endDateId).value);

        // Validar si ambas fechas están seleccionadas
        if (!startDate || !endDate || isNaN(startDate) || isNaN(endDate)) {
            $(errorMessageSelector).addClass("d-none"); // Ocultar el mensaje de error si no están completas
            return false;
        }

        // Validar que la fecha de inicio sea anterior a la fecha de fin
        if (startDate >= endDate) {
            $(errorMessageSelector)
                .removeClass("d-none")
                .text("La fecha de inicio debe ser anterior a la fecha de fin.");
            return false;
        }

        // Calcular la diferencia en días
        const differenceInTime = endDate.getTime() - startDate.getTime();
        const differenceInDays = Math.floor(differenceInTime / (1000 * 3600 * 24));

        // Validar que el periodo cumpla con múltiplos del número de noches requeridas
        const remainder = differenceInDays % requiredNights;
        const closestMultiple = Math.round(differenceInDays / requiredNights) * requiredNights;

        if (remainder === 0) {
            $(errorMessageSelector).addClass("d-none"); // Ocultar mensaje de error
            return true;
        } else {
            const daysOff = remainder > 0 ? remainder : requiredNights - Math.abs(remainder);
            const suggestion = closestMultiple > differenceInDays ? closestMultiple : closestMultiple + requiredNights;

            // Mostrar mensaje con sugerencia y diferencia de días
            $(errorMessageSelector)
                .removeClass("d-none")
                .html(`
                El periodo actual es de <strong>${differenceInDays} días</strong>. 
                Debe ser un múltiplo de ${requiredNights} noches.<br>
                <strong>Recomendación:</strong> Ajuste a ${suggestion} días 
                (${Math.abs(suggestion - differenceInDays)} días ${suggestion > differenceInDays ? 'faltan' : 'sobran'}).
            `);
            return false;
        }
    }
    /**
     * Función para asignar el valor de un input a otro
     * @param {string} sourceId - ID del input de origen
     * @param {string} targetId - ID del input de destino
     * @param {function} transformFn - (Opcional) Función para transformar el valor antes de asignarlo
     */
    function assignInputValue(sourceId, targetId, transformFn = null) {

        const sourceValue = document.getElementById(sourceId).value;
        const targetInput = document.getElementById(targetId);

        // Aplicar transformación si se proporciona una función
        const finalValue = transformFn ? transformFn(sourceValue) : sourceValue;

        // Asignar el valor transformado o el valor original
        targetInput.value = finalValue;

        // Simular eventos onchange y oninput
        targetInput.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        targetInput.dispatchEvent(new Event('change', {
            bubbles: true
        }));
        targetInput.dispatchEvent(new Event('input', {
            bubbles: true
        }));
        targetInput.dispatchEvent(new Event('blur', {
            bubbles: true
        }));
    }

    // Helpers fin



    $(document).ready(function() {
        let currentStep = 1;
        const totalSteps = 4;

        // Función para actualizar el encabezado
        function updateStepHeader(currentStep, titlesArray) {
            const defaultTitle = `Paso ${currentStep}`;
            const title = titlesArray[currentStep] ? titlesArray[currentStep] : defaultTitle;

            // Actualizar el texto del encabezado
            const stepHeader = $("#step-header");
            stepHeader.text(title);

            // Aplicar animación
            stepHeader.addClass("animate-title");
            setTimeout(() => {
                stepHeader.removeClass("animate-title");
            }, 1000); // Duración de la animación (1 segundo)
        }

        // Controlar flujo de pasos
        $(".next-step").click(function() {
            // Seleccionar los campos visibles del paso actual
            const currentStepFields = $(`#step-${currentStep} :input`);
            $(this).show();
            // console.log(`Validando los campos del paso ${currentStep}`);
            // console.log(currentStepFields);

            // Validar solo los campos visibles del paso actual
            if (currentStepFields.length && currentStepFields[0].checkValidity()) {
                // Ocultar el paso actual
                $(`#step-${currentStep}`).addClass("d-none");

                if (currentStep == 3) {
                    const tarjetaSeleccionada = $(".tarjeta-checkbox:checked");
                    if (tarjetaSeleccionada.length === 0) {
                        alert("Por favor, selecciona una tarjeta antes de continuar.");

                        // Cambiar currentStep a 2 para evitar el bucle infinito
                        currentStep = 2;

                        // Simular clic en el botón para volver a intentar avanzar
                        $(".next-step").trigger("click");

                        return; // Detener avance si no hay selección
                    }
                }


                // Mostrar el siguiente paso
                currentStep++;
                $(`#step-${currentStep}`).removeClass("d-none");
                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
                // Actualizar barra de progreso
                updateProgress();

                // Mostrar el botón "Anterior" si no es el primer paso
                if (currentStep > 1) {
                    $(".prev-step").removeClass("d-none");
                }

                // Cambiar texto del botón si es el último paso
                if (currentStep === totalSteps) {
                    $(this).hide();
                }

                // Copiar valores de `step-1` a `step-2` al avanzar al paso 2
                if (currentStep === 2) {
                    copyStep1ToStep2();
                    calculateDifferenceAndPercentage();
                    assignInputValue('inflation-20-years', 'field16');

                    // assignInputValue('inflation-5-years', 'field11');

                }
                if (currentStep == 3) {
                    deseleccionarAmbosYSimularEventomostrarResultadosCostosComparacion();
                }
                if (currentStep == 4) {
                    calcularResumenFinal();
                }
                // console.log(currentStep);
            } else {
                // Mostrar errores en campos faltantes
                currentStepFields.each(function() {
                    if (!this.checkValidity()) {
                        this.reportValidity(); // Mostrar mensajes de error nativos
                        return false; // Detener al primer error
                    }
                });
            }
        });

        // Función para copiar valores del `step-1` al `step-2`
        function copyStep1ToStep2() {
            // Mapeo de campos entre `step-1` y `step-2`
            const fieldMapping = {
                // field0: "field10", // Tour Operador
                field2: "field11", // Monto
                field3: "field12", // Fecha de Inicio
                field4: "field13", // Fecha de Fin
                field5: "field14", // Hotel
                field6: "field15", // Habitación
            };

            // Iterar sobre el mapeo y copiar los valores
            Object.keys(fieldMapping).forEach((step1FieldId) => {
                const step1Value = $(`#${step1FieldId}`).val(); // Obtener valor de `step-1`
                const step2FieldId = fieldMapping[step1FieldId];
                $(`#${step2FieldId}`).val(step1Value); // Asignar valor a `step-2`
            });

            // console.log("Valores copiados de `step-1` a `step-2`");
        }


        $(".prev-step").click(function() {
            // Ocultar el paso actual
            $(`#step-${currentStep}`).addClass("d-none");

            // Mostrar el paso anterior
            currentStep--;
            $(`#step-${currentStep}`).removeClass("d-none");

            // Actualizar barra de progreso
            updateProgress();

            // Ocultar el botón "Anterior" si es el primer paso
            if (currentStep === 1) {
                $(this).addClass("d-none");
            }
            if (currentStep == 3) {
                $(".next-step").show();
            }
            // Cambiar texto del botón "Siguiente" si ya no es el último paso
            $(".next-step").text("Siguiente");
        });

        function updateProgress() {
            $(".progress-step").each(function() {
                const step = $(this).data("step");
                if (step <= currentStep) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }
            });

            // Actualizar encabezado
            const stepTitles = {
                1: "Semana vacacional tradicional",
                2: "Semana vacacional como Socio Circle",
                3: "¿Qué prefieres?",
                4: "RESUMEN",
            };
            updateStepHeader(currentStep, stepTitles);
            // $("#step-header").text(`Paso ${currentStep}`);
            // Actualizar encabezado fin

        }



        $("#field0").change(function() {
            const selectedValue = $(this).val();

            if (selectedValue === "otro") {
                // Mostrar el campo de texto para especificar otro operador
                $("#custom-operator-container").removeClass("d-none");
                $("#field1").attr("required", true); // Hacerlo obligatorio
            } else {
                // Ocultar el campo de texto si no se selecciona "Elegir otro"
                $("#custom-operator-container").addClass("d-none");
                $("#field1").removeAttr("required");
            }
        });



        //VALDIACION DE NOCHES INICIO
        $("#field3, #field4").on("change", function() {
            const requiredNights = 1; // Configuración dinámica del número de noches
            const errorMessageSelector = "#date-error-1"; // Selector del contenedor de mensajes de error
            validateDatePeriod("field3", "field4", requiredNights, errorMessageSelector);
        });
        //VALIDACION DE FECHAS FIN
    });



    ///vaida monto inicio 
    let lastValidatedAmount = 0; // Último monto validado
    let referenceAmount = 12000; // Monto de referencia inicial

    function validateAmountMontoTourOperador(event) {
        let input = event.target;
        let value = parseCurrency(input.value); // Convertir a número

        // Calcular el umbral en base al 120% del monto de referencia
        const threshold = referenceAmount * 1.2;


        const alertBox = document.getElementById("alert-box-monto-tour-operador");
        if (!alertBox.classList.contains("d-none")) {
            alertBox.classList.add("d-none");
        }


        // Validar si el valor supera el umbral (40% mayor o menor al valor de referencia)
        if (value > referenceAmount && (value - lastValidatedAmount >= threshold || value - lastValidatedAmount <= -threshold)) {
            lastValidatedAmount = value; // Actualizar el último monto validado
            referenceAmount = value; // Actualizar el monto de referencia
            fetchDataFraseMontoTourOperador(value); // Hacer la solicitud a la API
        } else if (value <= referenceAmount) {
            // Aquí puedes manejar los casos en los que el valor no cumple la condición
        }
    }



    // Función para convertir el texto en un número (remueve caracteres no numéricos)
    function parseCurrency(value) {
        return parseFloat(value.replace(/[^0-9.-]+/g, "")) || 0; // Remover símbolos y convertir a número
    }


    // Función para realizar la solicitud a la API
    async function fetchDataFraseMontoTourOperador(amount) {
        let api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=obtenerFraseRandom'; ?>";

        try {
            let response = await fetch(api, {
                method: 'GET'
            });
            if (response.ok) {
                let data = await response.json();
                // console.log(`Datos de la API para el monto ${amount}:`, data);
                showAlertMontoTourOperador("Uy... " + data.message, "alert-warning");

            } else {
                // console.error("Error al consultar la API:", response.status);
                // showAlertMontoTourOperador(response.message, "alert-danger");
            }
        } catch (error) {
            console.error("Error de conexión con la API:", error);
            // showAlertMontoTourOperador("Error de conexión con la API.", "alert-danger");
        }
    }

    function showAlertMontoTourOperador(message, alertClass) {
        const alertBox = document.getElementById("alert-box-monto-tour-operador");
        alertBox.classList.remove('d-none');
        alertBox.className = `custom-alert alert ${alertClass} fade show`; // Agrega una clase personalizada
        alertBox.textContent = message; // Agrega el mensaje
    }
    async function fetchCalculoInflacion(pais, monto) {
        const api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=calcularInflacionConDetalle'; ?>";
        // console.log(monto);
        // document.getElementById('inflation-results').classList.remove('d-none');

        try {
            // Limpiar y convertir el monto a un número válido
            monto = parseFloat(monto.replace(/[^0-9.-]+/g, '')); // Eliminar caracteres no numéricos
            document.getElementById('inflation-results').classList.add('d-none');

            if (isNaN(monto) || monto <= 800) {
                // console.error("Monto inválido:", monto);
                return "";
            }

            const requestBody = {
                pais: pais,
                monto: monto
            };

            const response = await fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            if (response.ok) {
                const data = await response.json();
                //console.log("Respuesta de la API:", data);

                if (data.status) {
                    // Actualizar los valores de los resultados
                    const inflationData = data.data.acumulado;

                    // Acceder correctamente a las claves del objeto
                    document.getElementById('inflation-2-years').value = formatCurrencyValue(inflationData["2_anios"]);
                    document.getElementById('inflation-5-years').value = formatCurrencyValue(inflationData["5_anios"]);
                    document.getElementById('inflation-10-years').value = formatCurrencyValue(inflationData["10_anios"]);
                    document.getElementById('inflation-15-years').value = formatCurrencyValue(inflationData["15_anios"]);
                    document.getElementById('inflation-20-years').value = formatCurrencyValue(inflationData["20_anios"]);

                    // Mostrar los resultados
                    document.getElementById('inflation-results').classList.remove('d-none');
                } else {
                    // console.error("Error en la API:", data.message);
                    alert("Error al calcular la inflación: " + data.message);
                }
            } else {
                // console.error("Error al consultar la API:", response.status);
            }
        } catch (error) {
            console.error("Error de conexión con la API:", error);
        }
    }

    async function fetchCalculoInflacionConAhorroProyeccion(pais, monto) {
        const api = "<?php echo BASE_URL_PROJECT . 'app/api/v1/ventas/?action=calcularInflacionConDetalle'; ?>";
        // console.log(monto);
        // document.getElementById('inflation-results').classList.remove('d-none');

        try {
            // Limpiar y convertir el monto a un número válido
            monto = parseFloat(monto.replace(/[^0-9.-]+/g, '')); // Eliminar caracteres no numéricos
            document.getElementById('inflation-socio-results').classList.add('d-none');

            if (isNaN(monto) || monto <= 800) {
                // console.error("Monto inválido:", monto);
                return "";
            }

            const requestBody = {
                pais: pais,
                monto: monto
            };

            const response = await fetch(api, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });

            if (response.ok) {
                const data = await response.json();
                //console.log("Respuesta de la API:", data);

                if (data.status) {
                    // Actualizar los valores de los resultados
                    const inflationData = data.data.acumulado;


                    document.getElementById('inflation-socio-5-years').value = formatCurrencyValue(inflationData["5_anios"]);
                    document.getElementById('inflation-socio-10-years').value = formatCurrencyValue(inflationData["10_anios"]);
                    document.getElementById('inflation-socio-15-years').value = formatCurrencyValue(inflationData["15_anios"]);
                    document.getElementById('inflation-socio-20-years').value = formatCurrencyValue(inflationData["20_anios"]);
                    // Mostrar los resultados
                    document.getElementById('inflation-socio-results').classList.remove('d-none');
                    calculateDifferenceAndPercentage()
                } else {
                    // console.error("Error en la API:", data.message);
                    alert("Error al calcular la inflación: " + data.message);
                }
            } else {
                // console.error("Error al consultar la API:", response.status);
            }
        } catch (error) {
            console.error("Error de conexión con la API:", error);
        }
    }

    // Función para formatear los valores a moneda
    function formatCurrencyValue(value) {
        return `$${parseFloat(value).toFixed(2)}`;
    }
    //valida monto fin


    //paso 2 inicio
    // document.addEventListener("DOMContentLoaded", function() {
    //     // $('[data-toggle="tooltip"]').tooltip();

    // });
    // async function updateTooltipMontoInversionMembresia() {
    //     const dataUrl = 'assets/js/app/data-membresias.json';
    //     const membershipType = document.getElementById("field8").value;
    //     const selectField = document.getElementById("field8");
    //     const inputField = document.getElementById("field9"); // Campo que actualiza el placeholder y el title
    //     let tooltipText = "Selecciona un tipo de membresía";
    //     let placeholderText = ""; // Variable para el placeholder
    //     let titleText = ""; // Variable para el title del input

    //     try {
    //         // Realizar la petición para obtener los datos del JSON
    //         const response = await fetch(dataUrl);
    //         const membershipData = await response.json();

    //         // Verificar si la membresía seleccionada existe en los datos
    //         if (membershipData[membershipType]) {
    //             const options = membershipData[membershipType];

    //             // Crear el texto del tooltip con los datos del JSON
    //             tooltipText = options
    //                 .map(
    //                     (option, index) =>
    //                         `Opción ${index + 1}: Inversión: $${option.investment.toLocaleString()} | Total Semanas: ${option.weeksTotal} | Puntos: ${option.points}`
    //                 )
    //                 .join('\n');

    //             // Crear la lista de montos disponibles para el placeholder y el title
    //             const investmentValues = options.map(option => `$${option.investment.toLocaleString()}`);
    //             placeholderText = `Montos permitidos: ${investmentValues.join(', ')}`;
    //             titleText = `Montos permitidos: ${investmentValues.join(', ')}`;
    //         } else {
    //             tooltipText = "Datos no disponibles para esta membresía";
    //             placeholderText = "Selecciona una membresía válida";
    //             titleText = "Selecciona una membresía válida";
    //         }
    //     } catch (error) {
    //         console.error('Error al cargar el archivo JSON:', error);
    //         tooltipText = "Error al cargar los datos de membresías";
    //         placeholderText = "Error al cargar los datos";
    //         titleText = "Error al cargar los datos";
    //     }

    //     // Actualizar el tooltip dinámicamente
    //     $(selectField).tooltip('dispose').attr('title', tooltipText).tooltip();

    //     // Actualizar el placeholder y el title del input
    //     if (inputField) {
    //         inputField.placeholder = placeholderText;
    //         inputField.title = titleText;
    //     }
    // }


    function calculateDifferenceAndPercentage() {
        //const montoField = document.getElementById("field11"); // Monto
        const montoField = document.getElementById("inflation-socio-20-years"); // Monto
        // console.log(montoField.value);

        const semanaTradicionalField = document.getElementById("field16"); // Semana Tradicional
        const ahorroMontoField = document.getElementById("field18"); // Ahorro en monto
        const ahorroPorcentajeField = document.getElementById("field19"); // Ahorro en porcentaje
        const multiplicadorField = document.getElementById("field20"); // Multiplicador
        const socioCircleField = document.getElementById("field17"); // Monto adicional (Socio Circle)
        //console.log(montoField);
        // Obtener valores de los campos
        //const monto = parseFloat(montoField.value.replace(/[^0-9.-]+/g, "")) || 0;
        const monto = parseFloat(montoField.value.replace(/[^0-9.-]+/g, '')) || 0;
        const semanaTradicional = parseFloat(semanaTradicionalField.value.replace(/[^0-9.-]+/g, "")) || 0;
        const multiplicador = parseInt(multiplicadorField.value) || 1;
        socioCircleField.value = (monto);
        if (monto && semanaTradicional) {
            // Calcular diferencia
            const ahorroMonto = (semanaTradicional - monto) * multiplicador;

            // Calcular porcentaje de ahorro
            const ahorroPorcentaje = ahorroMonto > 0 ?
                ((ahorroMonto / (semanaTradicional * multiplicador)) * 100).toFixed(2) :
                0;

            // Actualizar los campos de ahorro
            ahorroMontoField.value = `$${ahorroMonto.toFixed(2)}`;
            ahorroPorcentajeField.value = `${ahorroPorcentaje}%`;
        } else {
            // Si no hay valores válidos, reiniciar campos de ahorro
            ahorroMontoField.value = "";
            ahorroPorcentajeField.value = "";
        }
    }



    //paso 2 fin

    //paso 3 inicio

    async function obtenerDatosMembresias() {
        const response = await fetch("<?php echo BASE_URL_PROJECT . 'assets/js/app/data-membresias.json'; ?>");
        const datos = await response.json();
        // console.log(datos);
        return datos;
    }
    // Función para encontrar los montos adyacentes en una categoría
    function encontrarDosMontosMasCercanos(dataMembresias, monto) {
        let resultadoFinal = [];
        let diferencias = [];

        // Iterar sobre todas las categorías
        for (let categoria in dataMembresias) {
            const datos = dataMembresias[categoria];

            // Validar que los datos sean un arreglo
            if (!Array.isArray(datos)) {
                console.warn(`La categoría ${categoria} no contiene un arreglo:`, datos);
                continue;
            }

            datos.forEach(item => {
                // Calcular la diferencia absoluta con el monto proporcionado
                const diferencia = Math.abs(monto - item.investment);

                // Guardar el registro junto con la diferencia y la categoría
                diferencias.push({
                    ...item,
                    diferencia,
                    categoria,
                });
            });
        }

        // Ordenar por la diferencia más cercana
        diferencias.sort((a, b) => a.diferencia - b.diferencia);

        // Seleccionar los dos montos más cercanos
        if (diferencias.length > 0) {
            resultadoFinal.push(diferencias[0]); // Más cercano o igual
        }
        if (diferencias.length > 1) {
            resultadoFinal.push(diferencias[1]); // El siguiente más cercano
        }

        return resultadoFinal;
    }




    function limpiarValor(valor) {
        // Eliminar símbolo de dólar y comas, y convertir a número
        return parseFloat(valor.replace(/[$,]/g, ''));
    }

    // Función principal para manejar el cambio en los checkboxes
    async function manejarSeleccionPropuestaPresupuesto() {
        const checkboxCalidad = document.getElementById('checkboxCalidad');
        const checkboxAhorro = document.getElementById('checkboxAhorro');
        const resultadosContainer = document.getElementById('resultados-montos-comparacion-container');

        // Asegurarse de que solo uno de los checkboxes esté seleccionado
        if (this.id === 'checkboxCalidad' && this.checked) {
            checkboxAhorro.checked = false;
        } else if (this.id === 'checkboxAhorro' && this.checked) {
            checkboxCalidad.checked = false;
        }

        // Verificar cuál checkbox está seleccionado
        if (!checkboxCalidad.checked && !checkboxAhorro.checked) {
            console.log('Ninguna opción seleccionada');
            // Ocultar el contenedor si no hay selección
            resultadosContainer.classList.add('d-none');
            return;
        }

        resultadosContainer.classList.remove('d-none');

        // Obtener los valores de los inputs correspondientes
        const montoOperadorRaw = document.getElementById('inflation-20-years').value;
        const montoCircleRaw = document.getElementById('field18').value;

        // Limpiar y convertir los valores
        const montoOperador = limpiarValor(montoOperadorRaw);
        const montoCircle = limpiarValor(montoCircleRaw);

        // Obtener datos de membresías
        const dataMembresias = await obtenerDatosMembresias();

        // Buscar los dos montos más cercanos
        let resultados;
        if (checkboxCalidad.checked) {
            resultados = encontrarDosMontosMasCercanos(dataMembresias, montoOperador);
        } else if (checkboxAhorro.checked) {
            resultados = encontrarDosMontosMasCercanos(dataMembresias, montoCircle);
        }

        console.log('Resultados:', resultados);

        // Mostrar resultados en el DOM
        mostrarResultadosCostosComparacion(resultados);
    }

    function deseleccionarAmbosYSimularEventomostrarResultadosCostosComparacion() {
        const checkboxCalidad = document.getElementById('checkboxCalidad');
        const checkboxAhorro = document.getElementById('checkboxAhorro');

        // Deseleccionar ambos checkboxes
        checkboxCalidad.checked = false;
        checkboxAhorro.checked = false;

        // Crear y despachar eventos onchange simulados
        const eventCalidad = new Event('change', {
            bubbles: true
        });
        const eventAhorro = new Event('change', {
            bubbles: true
        });

        checkboxCalidad.dispatchEvent(eventCalidad);
        checkboxAhorro.dispatchEvent(eventAhorro);

        console.log('Ambos checkboxes deseleccionados y eventos simulados.');
    }

    function mostrarResultadosCostosComparacion(resultados) {
        const contenedorResultados = document.getElementById('resultados-montos-comparacion-container');
        contenedorResultados.innerHTML = ''; // Limpiar resultados previos

        resultados.forEach(({
            categoria,
            investment,
            weeksPerYear,
            weeksTotal
        }, index) => {
            // Determinar el color del encabezado según la categoría
            let headerColor = '';
            switch (categoria.toUpperCase()) {
                case 'INFINITE RED':
                    headerColor = 'background-color: #dc3545; color: white;'; // Rojo
                    break;
                case 'INFINITE BLACK':
                    headerColor = 'background-color: #343a40; color: white;'; // Negro
                    break;
                case 'INFINITE BLUE':
                    headerColor = 'background-color: #007bff; color: white;'; // Azul
                    break;
                default:
                    headerColor = 'background-color: #6c757d; color: white;'; // Gris (default)
            }

            // Generar el HTML de la tarjeta con inputs hidden
            const resultadoHTML = `
            <div class="col-12 col-md-6 mb-3">
                <div class="card border-success tarjeta-seleccionable" id="tarjeta-${index}">
                    <div class="card-header text-white pt-3" style="${headerColor}">
                        ${categoria.toUpperCase()}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Monto: $${investment.toLocaleString()}</h5>
                        <p class="card-text">
                            <strong>Semanas Totales:</strong> ${weeksTotal}<br>
                            <strong>Distribución Anual:</strong> ${weeksPerYear.join(', ')}
                        </p>
                        <div class="form-check">
                            <input 
                                class="form-check-input tarjeta-checkbox" 
                                type="checkbox" 
                                id="resultado-checkbox-${index}" 
                                value="${index}">
                            <label class="form-check-label" for="resultado-checkbox-${index}">Seleccionar esta opción</label>
                        </div>
                        <!-- Inputs hidden para almacenar datos -->
                        <input type="hidden" id="hidden-categoria-${index}" name="categoria-${index}" value="${categoria}">
                        <input type="hidden" id="hidden-investment-${index}" name="investment-${index}" value="${investment}">
                        <input type="hidden" id="hidden-weeksTotal-${index}" name="weeksTotal-${index}" value="${weeksTotal}">
                        <input type="hidden" id="hidden-weeksPerYear-${index}" name="weeksPerYear-${index}" value="${weeksPerYear.join(', ')}">
                    </div>
                </div>
            </div>
        `;
            contenedorResultados.innerHTML += resultadoHTML;
        });

        // Asignar eventos de selección a los checkboxes
        const checkboxes = document.querySelectorAll('.tarjeta-checkbox');
        checkboxes.forEach((checkbox, index) => {
            checkbox.addEventListener('change', function() {
                manejarSeleccionTarjeta(index, this.checked);
            });
        });
    }

    // Función para manejar la selección de una tarjeta
    function manejarSeleccionTarjeta(index, isChecked) {
        const tarjeta = document.getElementById(`tarjeta-${index}`);
        const checkboxes = document.querySelectorAll('.tarjeta-checkbox');

        // Si la tarjeta está seleccionada, desmarcar las demás y resaltar esta
        if (isChecked) {
            checkboxes.forEach((checkbox, i) => {
                if (i !== index) {
                    checkbox.checked = false; // Desmarcar los demás checkboxes
                    const tarjetaOtras = document.getElementById(`tarjeta-${i}`);
                    tarjetaOtras.classList.remove('resaltado'); // Quitar resaltado de las demás tarjetas
                }
            });
            tarjeta.classList.add('resaltado'); // Resaltar la tarjeta seleccionada
        } else {
            tarjeta.classList.remove('resaltado'); // Quitar el resaltado si se desmarca
        }
    }


    function obtenerValoresMembresiaSeleccionada() {
        // Encontrar el checkbox marcado
        const checkboxSeleccionado = document.querySelector('.tarjeta-checkbox:checked');

        if (checkboxSeleccionado) {
            // Obtener el índice de la tarjeta seleccionada
            const index = checkboxSeleccionado.value;

            // Acceder a los valores almacenados en los inputs hidden
            const categoria = document.getElementById(`hidden-categoria-${index}`).value;
            const investment = document.getElementById(`hidden-investment-${index}`).value;
            const weeksTotal = document.getElementById(`hidden-weeksTotal-${index}`).value;
            const weeksPerYear = document.getElementById(`hidden-weeksPerYear-${index}`).value;

            // Crear un objeto con los valores seleccionados
            const valoresSeleccionados = {
                categoria,
                investment: parseFloat(investment),
                weeksTotal: parseInt(weeksTotal, 10),
                weeksPerYear: weeksPerYear.split(', ').map(Number) // Convertir a un array de números
            };

            console.log('Valores seleccionados:', valoresSeleccionados);
            return valoresSeleccionados;
        } else {
            console.log('No hay tarjetas seleccionadas.');
            return null;
        }
    }

    let valorSemanasOriginal =0;
    let valorExperienciaOriginal = 0;
    function calcularResumenFinal() {
    // Obtener valores de los inputs brutos
    let montoOperadorRaw = document.getElementById("inflation-20-years").value;
    let montoMeliaRaw = document.getElementById("inflation-socio-20-years").value;

    // Limpiar valores
    let montoOperador = limpiarValor(montoOperadorRaw);
    let montoMelia = limpiarValor(montoMeliaRaw);

    // Obtener valores de la membresía seleccionada
    let valoresSeleccionados = obtenerValoresMembresiaSeleccionada();
    if (!valoresSeleccionados) {
        console.log('No hay valores de membresía seleccionados.');
        return;
    }

    const { categoria, weeksTotal, investment } = valoresSeleccionados;

    // Definir valores por categoría
    let valorPorSemana, semanaConExperiencia;
    let aniosByMembresiaShow=0;
    console.log(aniosByMembresiaShow);
    console.log(categoria);

    switch (categoria.toUpperCase()) {
        case 'INFINITE RED':
            valorPorSemana = 3300;
            aniosByMembresiaShow=10;
            semanaConExperiencia = 1750;
            break;
        case 'INFINITE BLACK':
            valorPorSemana = 1500;
            aniosByMembresiaShow=7;

            semanaConExperiencia = 2750;
            break;
        case 'INFINITE BLUE':
            valorPorSemana = 3300;
            aniosByMembresiaShow=5;
            semanaConExperiencia = 900;
            break;
        default:
            console.warn('Categoría no reconocida:', categoria);
            return;
    }
    console.log(aniosByMembresiaShow);


    // Calcular total de semanas con experiencia
    const valorSemanasTotales = weeksTotal * (valorPorSemana + semanaConExperiencia);

    // Calcular aumentos basados en el array de porcentajes
    const arrayDeAumento = [4.5, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 3.5, 4.0, 4.1, 4.0, 4.2, 4.1, 4.0, 4.3, 4.2, 4.1, 4.0, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0];
    let baseAumento = valorSemanasTotales;
    const resultadosAnios = [];
    const aniosByMembresia=[5, 7, 10];

    aniosByMembresia.forEach(anio => {
        let acumulado = baseAumento;
        for (let i = 0; i < anio; i++) {
            acumulado += (acumulado * arrayDeAumento[i] / 100);
        }
        resultadosAnios.push(acumulado.toFixed(2));
    });


       // Mostrar desgloses en el DOM
    document.getElementById("inputSemanasResultado").value = valorPorSemana.toFixed(2);
    document.getElementById("inputSemanaExperienciaResultado").value = semanaConExperiencia.toFixed(2);
   
    
    valorExperienciaOriginal = parseFloat(document.getElementById("inputSemanaExperienciaResultado").value);
    document.getElementById("inputSemanaExperienciaResultadoSumaDeAmbos").value = (semanaConExperiencia+valorPorSemana).toFixed(2);
    console.log("calculo 2");
    document.getElementById("inputSemanaExperienciaResultadoSumaDeAmbosFinalResult").value =  (semanaConExperiencia+valorPorSemana).toFixed(2);
    document.getElementById("inputMontoCalculoSemanaResultadoTotalProyeccionAniosProyectar").value =  aniosByMembresiaShow;

    
   
    // Calcular resultados finales
    const resultadoFinal = resultadosAnios[0]; // Tomando el de 5 años como ejemplo
    const totalFinal = parseFloat(resultadoFinal) + montoOperador;
    const totalOperarConMelia = investment + montoMelia;
    const diferencia = totalFinal - totalOperarConMelia;
    const porcentajeAhorro = ((diferencia / totalOperarConMelia) * 100).toFixed(2);

    // Mostrar resultados en inputs
    document.getElementById("inputMontoCalculoSemanaResultado").value = resultadoFinal;
 valorSemanasOriginal = parseFloat(document.getElementById("inputMontoCalculoSemanaResultado").value);
    console.log(document.getElementById("inputMontoCalculoSemanaResultado").value);

    document.getElementById("inputMontoCalculoSemanaResultadoTotalProyeccion").value = resultadoFinal;

    
    document.getElementById("inputMontoTourOperadorResultado").value = totalFinal.toFixed(2);
    document.getElementById("inputValorMembresiaResultado").value = investment.toFixed(2);
    document.getElementById("inputValorOperarConMeliaResultado").value = montoMelia.toFixed(2);
    document.getElementById("inputTotalTourOperadorResultado").value = totalFinal.toFixed(2);
    document.getElementById("inputTotalOperarConMeliaResultado").value = totalOperarConMelia.toFixed(2);
    document.getElementById("inputDiferenciaAhorro").value = `${diferencia.toFixed(2)} (${porcentajeAhorro}%)`;
}


    // Agregar eventos a los checkboxes
    document.getElementById('checkboxCalidad').addEventListener('change', manejarSeleccionPropuestaPresupuesto);
    document.getElementById('checkboxAhorro').addEventListener('change', manejarSeleccionPropuestaPresupuesto);


    

    function actualizarValoresSumaSemanasTotales() {
        // Obtener el divisor seleccionado
        const divisor = parseInt(document.getElementById("selectDivisor").value);
        
        // Calcular nuevos valores utilizando las variables originales
        const nuevoValorSemanas = (valorSemanasOriginal / divisor).toFixed(2);
        console.log(valorSemanasOriginal);
        // Actualizar los inputs con los nuevos valores calculados
        document.getElementById("inputMontoCalculoSemanaResultado").value = nuevoValorSemanas;

        
    }

    //paso 3 fin
</script>
</body>

</html>