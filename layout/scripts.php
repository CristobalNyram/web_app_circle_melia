<!-- Core Vendors JS -->
<script src="assets/js/vendors.min.js"></script>

<!-- page js -->
<script src="assets/vendors/chartjs/Chart.min.js"></script>
<!-- <script src="assets/js/pages/dashboard-default.js"></script> -->
<!-- <script src="assets/js/app/main.js"></script> -->

<!-- Core JS -->
<script src="assets/js/app.min.js"></script>
<!-- <script>
    // Función para calcular el aumento de la renta
    function calcularInflacion(rentaInicial, tasaInflacion) {
        let rentas = [];
        let renta = parseFloat(rentaInicial);

        for (let i = 1; i <= 5; i++) {
            renta = renta + (renta * (tasaInflacion / 100));
            rentas.push(renta.toFixed(2));
        }

        return rentas;
    }

    // Agregamos un evento al botón de calcular
    document.getElementById("button-calculate").addEventListener("click", function () {
        const rentaInicial = document.querySelector(".form-control").value;
        const tasaInflacion = 5; // Puedes cambiar esta tasa de inflación

        if (!rentaInicial || isNaN(rentaInicial)) {
            alert("Por favor, ingrese un valor válido para la renta inicial.");
            return;
        }

        const rentasCalculadas = calcularInflacion(rentaInicial, tasaInflacion);

        // Llamar a la función para actualizar el gráfico
        actualizarGrafico(rentasCalculadas);
    });

    // Función para actualizar el gráfico
    function actualizarGrafico(rentas) {
        const ctx = document.getElementById('revenue-chart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: ['1er Año', '2do Año', '3er Año', '4to Año', '5to Año'], // Etiquetas de los años
                datasets: [{
                    label: 'Costo de la renta en los próximos años',
                    data: rentas, // Datos de las rentas calculadas
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    }
</script> -->

 
<!-- <script>
        // Datos de inflación (extraídos del archivo)
        const inflationData = {
            'México': [5.0, 4.8, 4.5, 4.3, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6],
            'Estados Unidos': [3.2, 2.9, 2.7, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9],
            'Canadá': [2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 1.5],
            'España': [3.4, 3.2, 3.0, 2.8, 2.6, 2.5, 2.4, 2.3, 2.2, 2.1],
            'Chile': [4.8, 4.5, 4.3, 4.0, 3.8, 3.6, 3.4, 3.3, 3.2, 3.0],
            'Reino Unido': [3.1, 2.9, 2.7, 2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9],
            'years': [2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033]
        };

        // Crear el gráfico
        const ctx = document.getElementById('inflation-chart').getContext('2d');
        const inflationChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: inflationData.years,
                datasets: Object.keys(inflationData).filter(country => country !== 'years').map(country => ({
                    label: country,
                    data: inflationData[country],
                    borderColor: getRandomColor(),
                    fill: false
                }))
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
                            text: 'Tasa de Inflación (%)'
                        }
                    }
                }
            }
        });

        // Función para calcular la devaluación de la cantidad ingresada
        function calculate() {
            const amountInput = document.getElementById('amount-input').value;
            const yearInput = document.getElementById('year-input').value;
            const selectedCountry = document.getElementById('country-select').value;
            const amount = parseFloat(amountInput);
            const startYear = parseInt(yearInput);

            if (isNaN(amount) || isNaN(startYear) || startYear < 2024 || startYear > 2033) {
                alert('Por favor ingrese una cantidad válida y un año entre 2024 y 2033');
                return;
            }

            const inflationRates = inflationData[selectedCountry];
            let yearIndex = inflationData.years.indexOf(startYear);
            let adjustedAmount = amount;

            // Aplicar la inflación año por año para obtener el valor ajustado
            let yearResults = [`Valor inicial: $${amount.toFixed(2)} USD`];
            for (let i = yearIndex + 1; i < inflationRates.length; i++) {
                const inflationRate = inflationRates[i] / 100;
                adjustedAmount = adjustedAmount / (1 + inflationRate);
                yearResults.push(`En ${inflationData.years[i]}: $${adjustedAmount.toFixed(2)} USD (inflación: ${inflationRates[i]}%)`);
            }

            document.getElementById('result').innerHTML = yearResults.join('<br>');
        }

        // Función para obtener un color aleatorio para los gráficos
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
</script> -->
<script>
    // Datos de inflación (extraídos del archivo)
const inflationData = {
    'México': [5.0, 4.8, 4.5, 4.3, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6],
    'Estados Unidos': [3.2, 2.9, 2.7, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9],
    'Canadá': [2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 1.5],
    'España': [3.4, 3.2, 3.0, 2.8, 2.6, 2.5, 2.4, 2.3, 2.2, 2.1],
    'Chile': [4.8, 4.5, 4.3, 4.0, 3.8, 3.6, 3.4, 3.3, 3.2, 3.0],
    'Reino Unido': [3.1, 2.9, 2.7, 2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9],
    'years': [2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033]
};

// Crear el gráfico
let inflationChart;

function initializeChart() {
    const ctx = document.getElementById('inflation-chart').getContext('2d');
    inflationChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: inflationData.years,
            datasets: [{
                label: 'Valor del Dinero Ajustado (USD)',
                data: [],
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
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
                        text: 'Valor Ajustado (USD)'
                    },
                    ticks: {
                        beginAtZero: false
                    }
                }
            }
        }
    });
}

initializeChart(); // Inicializa el gráfico vacío al cargar la página

// Función para calcular la devaluación de la cantidad ingresada
function calculate() {
    const amountInput = document.getElementById('amount-input').value;
    const yearInput = document.getElementById('year-input').value;
    const selectedCountry = document.getElementById('country-select').value;
    const amount = parseFloat(amountInput);
    const startYear = parseInt(yearInput);

    if (isNaN(amount) || isNaN(startYear) || startYear < 2024 || startYear > 2033) {
        alert('Por favor ingrese una cantidad válida y un año entre 2024 y 2033');
        return;
    }

    const inflationRates = inflationData[selectedCountry];
    let yearIndex = inflationData.years.indexOf(startYear);
    let adjustedAmount = amount;

    // Limpiar el contenido de la tabla y actualizar la gráfica
    document.getElementById('table-body').innerHTML = '';
    const adjustedValues = [];

    // Aplicar la inflación año por año para obtener el valor ajustado
    let yearResults = [`Valor inicial: $${amount.toFixed(2)} USD`];
    for (let i = yearIndex + 1; i < inflationRates.length; i++) {
        const inflationRate = inflationRates[i] / 100;
        adjustedAmount = adjustedAmount / (1 + inflationRate);

        // Añadir fila a la tabla
        addRowToTable(inflationData.years[i], inflationRates[i], adjustedAmount);
        adjustedValues.push(adjustedAmount.toFixed(2));
    }

    document.getElementById('result').innerHTML = yearResults.join('<br>');

    // Actualizar gráfica con los valores ajustados
    updateChart(inflationData.years.slice(yearIndex + 1), adjustedValues);
}

// Función para agregar filas a la tabla
function addRowToTable(year, inflationRate, adjustedValue) {
    const tableBody = document.getElementById('table-body');
    const newRow = document.createElement('tr');
    newRow.classList.add('bg-white'); // Añadir la clase bg-white

    newRow.innerHTML = `
        <td>${year}</td>
        <td>${inflationRate}%</td>
        <td>$${adjustedValue.toFixed(2)} USD</td>
    `;
    tableBody.appendChild(newRow);
}

// Función para actualizar la gráfica con los valores ajustados
function updateChart(labels, adjustedValues) {
    inflationChart.data.datasets[0].data = adjustedValues; // Actualiza con los valores ajustados
    inflationChart.data.labels = labels; // Etiquetas de los años
    inflationChart.update();
}

</script>
</body>
</html>
