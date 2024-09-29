import themeColors from '../constant/theme-constant'

class DashboardDefault {

    static init() {
        const calculateButton = document.getElementById("button-calculate");

        // Evento al presionar el botón "Calcular"
        calculateButton.addEventListener('click', () => {
            const inputValue = document.querySelector('input').value;
            
            // Verificamos si el valor del input es un número válido
            if (isNaN(inputValue) || inputValue === "") {
                alert("Por favor ingrese un número válido");
                return;
            }
            
            const rentBase = parseFloat(inputValue);
            const rentYear1 = rentBase;
            const rentYear2 = rentBase * 1.03; // Incremento del 3% por segundo año
            const rentYear3 = rentBase * 1.06; // Incremento del 6% por tercer año

            // Si ya existe una gráfica, la destruimos antes de crear una nueva
            if (window.revenueChart) {
                window.revenueChart.destroy();
            }

            // Configuración de la gráfica con los datos calculados
            window.revenueChart = new Chart(document.getElementById("revenue-chart").getContext('2d'), {
                type: 'line',
                data: {
                    labels: ["Año 1", "Año 2", "Año 3"],
                    datasets: [{
                        label: 'Renta Anual',
                        backgroundColor: themeColors.transparent,
                        borderColor: themeColors.blue,
                        pointBackgroundColor: themeColors.blue,
                        pointBorderColor: themeColors.white,
                        pointHoverBackgroundColor: themeColors.blueLight,
                        pointHoverBorderColor: themeColors.blueLight,
                        data: [rentYear1.toFixed(2), rentYear2.toFixed(2), rentYear3.toFixed(2)]
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    maintainAspectRatio: false,
                    responsive: true,
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    tooltips: {
                        mode: 'index'
                    },
                    scales: {
                        xAxes: [{ 
                            gridLines: [{
                                display: false,
                            }],
                            ticks: {
                                display: true,
                                fontColor: themeColors.grayLight,
                                fontSize: 13,
                                padding: 10
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                drawBorder: false,
                                drawTicks: false,
                                borderDash: [3, 4],
                                zeroLineWidth: 1,
                                zeroLineBorderDash: [3, 4]  
                            },
                            ticks: {
                                display: true,
                                max: Math.ceil(rentYear3 / 10) * 10, // Ajustamos el máximo según el valor calculado
                                stepSize: 50,
                                fontColor: themeColors.grayLight,
                                fontSize: 13,
                                padding: 10
                            }  
                        }],
                    }
                }
            });
        });
    }
}

$(() => { DashboardDefault.init(); });
