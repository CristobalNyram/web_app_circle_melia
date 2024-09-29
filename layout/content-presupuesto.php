<div class="page-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card card-main-body">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Calculo de presupuesto de vacaciones ajustado por inflación</h5>
                        </div>
                        <div class="m-t-20">
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" id="vacation-budget-input"
                                    placeholder="Presupuesto de vacaciones en USD" aria-label="Presupuesto">
                                <input type="number" class="form-control" id="vacation-year-input"
                                    placeholder="Año inicial" step="0.0" aria-label="Año">
                                <select id="vacation-country-select" class="form-control">
                                    <option value="Estados Unidos">Estados Unidos</option>
                                    <option value="México">México</option>
                                    <option value="Canadá">Canadá</option>
                                    <option value="España">España</option>
                                    <option value="Chile">Chile</option>
                                    <option value="Reino Unido">Reino Unido</option>
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="button-calculate-vacation"
                                        onclick="calculateVacation()">Calcular Presupuesto de Vacaciones</button>
                                </div>
                            </div>
                        </div>

                        <div class="m-t-50">
                            <canvas style="height: 230px" class="chart" id="vacation-chart"></canvas>
                        </div>

                        <div class="m-t-20">
                            <h6 id="vacation-result"></h6>
                        </div>

                        <div class="row text-center mt-4">
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                      <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total5"></h5>
                                        
                                        <p class="card-text">Total por cinco años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total10"></h5>
                                        <p class="card-text">Total por diez años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total15"></h5>
                                        <p class="card-text">Total por quince años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total20"></h5>
                                        <p class="card-text">Total por veinte años</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered" id="vacation-summary-table">
                            <thead>
                                <tr class="bg-white">
                                    <th>Años</th>
                                    <th>Total Sin Inflación</th>
                                    <th>Total Con Inflación</th>
                                    <th>Descuento socio aproximado de <span style="color:red;">-20%  </span></th>
                                    <th>Descuento socio aproximado de <span style="color:red;">-40% </span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se añadirán dinámicamente las filas -->
                            </tbody>
                        </table>
                    </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 card-main-body">
            <div id="inflation-info" class="col-md-12">
            </div>
        </div>
    </div>
</div>
