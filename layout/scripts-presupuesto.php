<script src="assets/js/vendors.min.js"></script>
<script src="assets/vendors/chartjs/Chart.min.js"></script>
<script src="assets/js/app.min.js"></script>
<script>

    // CONTRIES 
// Variable global para almacenar el país seleccionado
let selectedCountryName = "México"; // Valor por defecto

const countries = {
    "México": {
        flag: "https://flagcdn.com/w320/mx.png"
    },
    "Estados Unidos": {
        flag: "https://flagcdn.com/w320/us.png"
    },
    "Canadá": {
        flag: "https://flagcdn.com/w320/ca.png"
    },
    "España": {
        flag: "https://flagcdn.com/w320/es.png"
    },
    "Chile": {
        flag: "https://flagcdn.com/w320/cl.png"
    },
    "Reino Unido": {
        flag: "https://flagcdn.com/w320/gb.png"
    },
    "Colombia": {
        flag: "https://flagcdn.com/w320/co.png"
    },
    "Argentina": {
        flag: "https://flagcdn.com/w320/ar.png"
    },
    "Brasil": {
        flag: "https://flagcdn.com/w320/br.png"
    },
    "Portugal": {
        flag: "https://flagcdn.com/w320/pt.png"
    },
    "República Dominicana": {
        flag: "https://flagcdn.com/w320/do.png"
    }
};

    window.onload = function () {
        const selectElement = document.getElementById("vacation-country-select");

        // Crear una lista para simular el select
        const ul = document.createElement("ul");
        ul.classList.add("country-list");

        // Crear el input de búsqueda como la primera opción
        const searchInputLi = document.createElement("li");
        const searchInput = document.createElement("input");
        searchInput.type = "text";
        searchInput.placeholder = "Buscar país...";
        searchInput.classList.add("country-search");

        // Agregar el input de búsqueda al elemento de la lista
        searchInputLi.appendChild(searchInput);
        ul.appendChild(searchInputLi);

        // Agregar países a la lista
        for (let country in countries) {
            const li = document.createElement("li");

            // Crear la imagen de la bandera
            const img = document.createElement("img");
            img.src = countries[country].flag;
            img.alt = `${country} flag`;
            img.classList.add("flag");

            // Crear el texto del país
            const span = document.createElement("span");
            span.textContent = country;

            // Agregar imagen y texto al elemento de la lista
            li.appendChild(img);
            li.appendChild(span);

            // Agregar evento de selección
            li.addEventListener("click", function (event) {
                event.stopPropagation();
                // Actualizar el país y bandera seleccionados
                selectElement.querySelector("span.selected-country").textContent = country;
                selectElement.querySelector("img.selected-flag").src = countries[country].flag;

                // Actualizar la variable global con el país seleccionado
                selectedCountryName = country;

                // Ocultar la lista después de la selección
                ul.style.display = "none";
                searchInput.value = ""; // Limpiar el campo de búsqueda al seleccionar un país
            });

            // Agregar el elemento de la lista al contenedor
            ul.appendChild(li);
        }

        // Crear la bandera y nombre seleccionado por defecto
        const selectedFlag = document.createElement("img");
        selectedFlag.classList.add("selected-flag");
        selectedFlag.src = countries["México"].flag; // Mostrar la bandera de México por defecto

        const selectedCountry = document.createElement("span");
        selectedCountry.classList.add("selected-country");
        selectedCountry.textContent = "México"; // Mostrar México por defecto

        // Agregar los elementos seleccionados al contenedor
        selectElement.appendChild(selectedFlag);
        selectElement.appendChild(selectedCountry);
        selectElement.appendChild(ul);

        // Evento para mostrar/ocultar la lista al hacer clic en el select
        selectElement.addEventListener("click", function (event) {
            ul.style.display = ul.style.display === "block" ? "none" : "block";
        });

        // Evento de búsqueda
        searchInput.addEventListener("input", function () {
            const filter = searchInput.value.toLowerCase();
            const liElements = ul.querySelectorAll("li:not(:first-child)"); // Excluir el input de búsqueda
            liElements.forEach(li => {
                const countryName = li.textContent.toLowerCase();
                if (countryName.includes(filter)) {
                    li.style.display = "";
                } else {
                    li.style.display = "none";
                }
            });
        });

        // Cerrar la lista cuando se hace clic fuera de ella
        document.addEventListener("click", function (event) {
            if (!selectElement.contains(event.target)) {
                ul.style.display = "none";
                searchInput.value = ""; // Limpiar el campo de búsqueda al cerrar
            }
        });

        // Impedir el cierre de la lista al hacer clic en el input de búsqueda
        searchInput.addEventListener("click", function (event) {
            event.stopPropagation(); // Evitar que el clic se propague
        });
    };

        // CONTRIES
    const inflationData = {
        "México": [5.0, 4.8, 4.5, 4.3, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 4.0, 4.1, 4.0, 4.2, 4.0, 4.1, 4.0, 4.1, 4.0, 4.2, 4.1, 4.0, 4.0, 4.1, 4.0, 4.2, 4.0, 4.1, 4.0, 4.0, 4.1, 4.0],
        "Estados Unidos": [3.2, 2.9, 2.7, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 2.5, 2.6, 2.5, 2.7, 2.5, 2.6, 2.5, 2.7, 2.6, 2.5, 2.6, 2.5, 2.4, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7],
        "Canadá": [2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 1.5, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.0, 2.1, 2.0, 1.9, 2.0, 1.9, 2.0, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7],
        "España": [3.4, 3.2, 3.0, 2.8, 2.6, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 2.1, 2.0, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.3, 2.2, 2.1, 2.0, 2.0, 1.9, 1.8],
        "Chile": [4.8, 4.5, 4.3, 4.0, 3.8, 3.6, 3.4, 3.3, 3.2, 3.0, 3.5, 3.6, 3.4, 3.5, 3.3, 3.5, 3.6, 3.4, 3.5, 3.4, 3.6, 3.5, 3.4, 3.5, 3.6, 3.7, 3.5, 3.4, 3.3, 3.2, 3.1, 3.0],
        "Reino Unido": [3.1, 2.9, 2.7, 2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 2.5, 2.6, 2.5, 2.6, 2.5, 2.7, 2.5, 2.6, 2.7, 2.5, 2.6, 2.5, 2.4, 2.5, 2.4, 2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8],
        "Colombia": [5.5, 5.3, 5.0, 4.8, 4.7, 4.5, 4.4, 4.2, 4.1, 4.0, 4.5, 4.6, 4.5, 4.7, 4.6, 4.8, 4.7, 4.8, 4.6, 4.5, 4.7, 4.5, 4.4, 4.3, 4.5, 4.6, 4.2, 4.1, 4.0, 4.0, 3.9, 3.8],
        "Argentina": [10.0, 9.5, 9.0, 8.5, 8.0, 7.5, 7.0, 6.5, 6.0, 5.5, 8.0, 7.5, 7.0, 6.5, 6.0, 5.5, 5.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8],
        "Brasil": [6.0, 5.8, 5.5, 5.2, 5.0, 4.8, 4.6, 4.5, 4.3, 4.2, 5.0, 5.2, 5.1, 5.3, 5.2, 5.0, 4.9, 4.8, 4.7, 4.6, 4.5, 4.4, 4.3, 4.5, 4.6, 4.7, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0],
        "Portugal": [2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.0, 2.1, 2.0, 2.1, 2.2, 2.3, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8],
        "República Dominicana": [4.5, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 3.5, 4.0, 4.1, 4.0, 4.2, 4.1, 4.0, 4.3, 4.2, 4.1, 4.0, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0],
        "years": [2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055]
    };


    let vacationChart;

    function initializeChart() {
        const ctx = document.getElementById('vacation-chart').getContext('2d');
        vacationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Presupuesto Ajustado (USD)',
                    data: [],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Año'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Presupuesto Ajustado (USD)'
                        },
                        ticks: {
                            beginAtZero: false
                        }
                    }
                }
            }
        });
    }

    initializeChart();

    function calculateVacation() {
    const budgetInput = document.getElementById('vacation-budget-input').value;
    const yearInput = document.getElementById('vacation-year-input').value;
    // Usar la variable global para el país seleccionado
    const selectedCountry = selectedCountryName; // Tomando de la variable global
    const budget = parseFloat(budgetInput);
    const startYear = parseInt(yearInput);

    // Validación de entrada
    if (isNaN(budget) || isNaN(startYear) || startYear < 2024 || startYear > 2045) {
        alert('Por favor ingrese un presupuesto válido y un año entre 2024 y 2045');
        return;
    }

    const inflationRates = inflationData[selectedCountry];

    // Asegúrate de que inflationRates existe y tiene datos
    if (!inflationRates) {
        alert('No se encontraron datos de inflación para el país seleccionado.');
        return;
    }

    let yearIndex = inflationData.years.indexOf(startYear);
    
    // Comprobar si el año inicial es válido
    if (yearIndex === -1) {
        alert('Por favor ingrese un año válido de la lista de años disponibles.');
        return;
    }

    let adjustedBudget = budget;
    let totalSinInflacion = budget;

    const adjustedValues = [];
    document.getElementById('vacation-result').innerHTML = '';
    document.getElementById('vacation-result-total5').innerHTML = '';

    let cincoAnios = 0, diezAnios = 0, quinceAnios = 0, veinteAnios = 0,treintaAnios = 0;
    // let budgetResults = `
    // <table class="table table-bordered table-striped">
    //     <tr class="bg-white">
    //         <th>Año</th>
    //         <th>Presupuesto Necesario (USD)</th>
    //         <th>Inflación (%)</th>
    //     </tr>`;

    for (let i = yearIndex; i < inflationRates.length + yearIndex; i++) {
        const inflationRate = inflationRates[i - yearIndex] / 100;
        adjustedBudget *= (1 + inflationRate);
        adjustedValues.push(adjustedBudget.toFixed(2));

        // budgetResults += `
        // <tr class="bg-white">
        //     <td>${inflationData.years[i]}</td>
        //     <td>$${adjustedBudget.toFixed(2)}</td>
        //     <td>${(inflationRates[i - yearIndex]).toFixed(2)}%</td>
        // </tr>`;

        // Acumular totales para 5, 10, 15 y 20 años
        if (i - yearIndex < 5) {
            cincoAnios += adjustedBudget;
        }
        if (i - yearIndex < 10) {
            diezAnios += adjustedBudget;
        }
        if (i - yearIndex < 15) {
            quinceAnios += adjustedBudget;
        
        }
        if (i - yearIndex < 20) {
            veinteAnios += adjustedBudget;
        }
        if (i - yearIndex < 30) {
            treintaAnios += adjustedBudget;
        }
    }

    // budgetResults += `</table>`;
    // document.getElementById('vacation-result').innerHTML = budgetResults;

    const tableElement = document.getElementById('vacation-summary-table');
    tableElement.classList.add('animate__animated', 'animate__fadeIn'); // Animación

    document.getElementById('vacation-result-total5').innerHTML = `$${cincoAnios.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;
    document.getElementById('vacation-result-total10').innerHTML = `$${diezAnios.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;
    document.getElementById('vacation-result-total15').innerHTML = `$${quinceAnios.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;
    document.getElementById('vacation-result-total20').innerHTML = `$${veinteAnios.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;
    document.getElementById('vacation-result-total30').innerHTML = `$${treintaAnios.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD`;


    // Generar la tabla de resumen
    generateSummaryTable(totalSinInflacion, cincoAnios, diezAnios, quinceAnios, veinteAnios,treintaAnios, inflationRates);

    // Actualizar gráfica
    updateChart(inflationData.years.slice(yearIndex), adjustedValues);
    displayInflationTable(inflationRates, selectedCountry);
}

    function generateSummaryTable(sinInflacion, cincoAnios, diezAnios, quinceAnios, veinteAnios, treintaAnios, inflationRates) {
        const tableBody = document.getElementById('vacation-summary-table').querySelector('tbody');
        tableBody.innerHTML = ''; // Limpiar cualquier fila previa

        // Acumulado sin inflación
        const cincoAcum = sinInflacion * 5;
        const diezAcum = sinInflacion * 10;
        const quinceAcum = sinInflacion * 15;
        const veinteAcum = sinInflacion * 20;
        const treintaAcum = sinInflacion * 30;

        const rows = [
            {
                years: 5,
                sinInflacion: cincoAcum,
                total: cincoAnios,
                minus20: cincoAnios * 0.2,
                minus40: cincoAnios * 0.4
            },
            {
                years: 10,
                sinInflacion: diezAcum,
                total: diezAnios,
                minus20: diezAnios * 0.2,
                minus40: diezAnios * 0.4
            },
            {
                years: 15,  // Nueva fila para 15 años
                sinInflacion: quinceAcum,
                total: quinceAnios,
                minus20: quinceAnios * 0.2,
                minus40: quinceAnios * 0.4
            },
            {
                years: 20,
                sinInflacion: veinteAcum,
                total: veinteAnios,
                minus20: veinteAnios * 0.2,
                minus40: veinteAnios * 0.4
            },
            {
                years: 30,
                sinInflacion: veinteAcum,
                total: treintaAnios,
                minus20: treintaAnios * 0.2,
                minus40: treintaAnios * 0.4
            }
        ];

        rows.forEach(row => {
            const tableRow = `
                <tr class="bg-white">
                    <td>${row.years}</td>
                    <td>$${row.sinInflacion.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD</td>
                    <td>$${row.total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD</td>

                    <td>$${row.minus20.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD</td>
                    <td>$${row.minus40.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} USD</td>

                </tr>
            `;
            tableBody.innerHTML += tableRow;
        });
    }

    function updateChart(labels, adjustedValues) {
        vacationChart.data.labels = labels;
        vacationChart.data.datasets[0].data = adjustedValues;
        vacationChart.update();
    }

    function displayInflationTable(inflationRates, country) {
        const inflationTableContainer = document.getElementById('inflation-info');
        inflationTableContainer.innerHTML = ''; // Limpiar tabla previa si existe

        const tableHTML = `
            <table class="table table-bordered mt-4">
                <thead>
                    <tr class="bg-white">
                        <th>Año</th>
                        <th>Tasa de Inflación (%)</th>
                    </tr>
                </thead>
                <tbody>
                    ${generateInflationTableRows(inflationRates)}
                </tbody>
            </table>
        `;

        inflationTableContainer.innerHTML = `<h6 class="bg-white p-2 m-2">Tasas de inflación proyectadas para ${country}</h6>${tableHTML}`;
    }

    function generateInflationTableRows(inflationRates) {
        let rows = '';
        for (let i = 0; i < inflationRates.length; i++) {
            rows += `
                <tr class="bg-white"> 
                    <td>${2024 + i}</td>
                    <td>${inflationRates[i].toFixed(2)}%</td>
                </tr>
            `;
        }
        return rows;
    }
</script>

</body>
</html>
