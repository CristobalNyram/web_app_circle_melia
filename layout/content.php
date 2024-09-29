<div class="page-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Calculo de inflación y valor futuro de dinero en USD</h5>
                            <div>
                                <div class="btn-group"></div>
                            </div>
                        </div>
                        <!-- Input para la cantidad de dinero, el año y el país -->
                        <div class="m-t-20">
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="amount-input"
                                    placeholder="Ingrese cantidad en USD" aria-label="Cantidad"
                                    aria-describedby="button-calculate">
                                <input type="number" class="form-control" id="year-input"
                                    placeholder="Ingrese año inicial (2024-2033)" aria-label="Año"
                                    aria-describedby="button-calculate">
                                <select id="country-select" class="form-control">
                                    <option value="Estados Unidos">Estados Unidos</option>
                                    <option value="México">México</option>
                                    <option value="Canadá">Canadá</option>
                                    <option value="España">España</option>
                                    <option value="Chile">Chile</option>
                                    <option value="Reino Unido">Reino Unido</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="button-calculate"
                                        onclick="calculate()">Calcular</button>
                                </div>
                            </div>
                        </div>
                        <!-- Gráfico de inflación -->
                        <div class="m-t-50">
                            <canvas style="height: 230px" class="chart" id="inflation-chart"></canvas>
                        </div>
                        <!-- Resultado del cálculo de la devaluación -->
                        <div class="m-t-20">
                            <h6 id="result"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <!-- Nota de advertencia -->
                <div class="alert alert-warning mt-4" role="alert">
                    Nota: Los cálculos se basan en datos de inflación estimados y pueden no reflejar cambios futuros en
                    las políticas monetarias o fluctuaciones económicas inesperadas.
                </div>
                <!-- Tabla dinámica de resultados -->
                <div class="table-responsive mt-4">
                    <table class="table table-striped" id="results-table">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Inflación (%)</th>
                                <th>Valor Ajustado (USD)</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Fuente</th>
                                <th>Enlace</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white">
                                <td>FMI - Perspectivas Económicas Mundiales</td>
                                <td><a href="#">www.fmi.org/economic-outlook</a></td>
                            </tr>
                            <tr class="bg-white">
                                <td>Banco Mundial</td>
                                <td><a href="#">www.bancomundial.org/inflation-data</a></td>
                            </tr>
                            <tr class="bg-white">
                                <td>Banco de México</td>
                                <td><a href="#">www.banxico.org.mx</a></td>
                            </tr>
                            <tr class="bg-white">
                                <td>OCDE - Inflación por País</td>
                                <td><a href="#">www.oecd.org/inflation-rates</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>