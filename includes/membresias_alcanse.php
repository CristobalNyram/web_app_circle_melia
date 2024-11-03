<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Membresías</h2>
            <div class="header-sub-title">
                <nav class="breadcrumb breadcrumb-dash">
                    <a href="#" class="breadcrumb-item"><i class="anticon anticon-home m-r-5"></i>Inicio</a>
                    <a class="breadcrumb-item">Membresías</a>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4>Calculadora de Semanas por Membresía</h4>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
