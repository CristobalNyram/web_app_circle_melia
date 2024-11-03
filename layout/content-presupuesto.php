<style>
    .custom-select {
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        cursor: pointer;
        position: relative;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .custom-select img.selected-flag {
        width: 24px;
        height: 16px;
        margin-right: 10px;
    }

    .country-search {
        width: 100%;
        padding: 5px;
        margin-bottom: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .country-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: none;
        /* Ocultar por defecto */
        border: 1px solid #ccc;
        position: absolute;
        top: 100%;
        width: 100%;
        background-color: white;
        z-index: 1;
    }

    .country-list li {
        padding: 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .country-list li:hover {
        background-color: #f1f1f1;
    }

    .country-list img.flag {
        width: 24px;
        height: 16px;
        margin-right: 10px;
    }

    .custom-select {
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 10px;
        cursor: pointer;
        position: relative;
        display: flex;
        align-items: center;
    }

    .custom-select img.selected-flag {
        width: 24px;
        height: 16px;
        margin-right: 10px;
    }

    .country-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        display: none;
        /* Ocultar por defecto */
        border: 1px solid #ccc;
        position: absolute;
        top: 100%;
        width: 100%;
        background-color: white;
        z-index: 1;
    }

    .country-list li {
        padding: 8px;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .country-list li:hover {
        background-color: #f1f1f1;
    }

    .country-list img.flag {
        width: 24px;
        height: 16px;
        margin-right: 10px;
    }

    /* Mostrar la lista cuando se hace clic */
    .custom-select.active .country-list {
        display: block;
    }
</style>
<div class="page-container">
    <div class="main-content">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card card-main-body">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>
                                <i class="anticon anticon-wallet"></i>

                                Calculo de presupuesto de vacaciones ajustado por inflación
                            </h5>
                        </div>
                        <div class="m-t-20">
                            <div class="input-group mb-3">
                                <select class="form-control" id="currency-select-input">
                                    <option selected value="USD">&#36;</option>
                                    <option value="EUR">&#8364;</option>
                                </select>
                                <input type="number" class="form-control" id="vacation-budget-input"
                                    placeholder="Presupuesto de vacaciones en USD" aria-label="Presupuesto">
                                <input type="number" class="form-control" id="vacation-year-input"
                                    placeholder="Año inicial" step="0.0" aria-label="Año">
                       

                                <div id="vacation-country-select" class="custom-select">
                                    <!-- Las opciones se cargarán dinámicamente -->
                                </div>

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

                        <div class="row text-center mt-3">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total5"></h5>

                                        <p class="card-text">Total por cinco años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total10"></h5>
                                        <p class="card-text">Total por diez años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total15"></h5>
                                        <p class="card-text">Total por quince años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total20"></h5>
                                        <p class="card-text">Total por veinte años</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <i class="anticon anticon-dollar" style="font-size: 24px; color: green;"></i>
                                        <h5 class="card-title" id="vacation-result-total30"></h5>
                                        <p class="card-text">Total por 30 años</p>
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
                                        <th>Descuento socio aproximado de <span style="color:red;">-20% </span></th>
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