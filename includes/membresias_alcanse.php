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
<script>
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
                li.textContent = `Año ${index + 1}: ${weeks} semanas`;
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
                        li.textContent = `Año ${index + 1}: ${weeks} semanas`;
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
                        li.textContent = `Año ${index + 1}: ${weeks} semanas`;
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
</script>