<?php
include '../../../database/db.php'; // Conexión a la base de datos

// Listar las ventas por competencia
function listVentasCompetencia()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $competenciaId = $data['competenciaId'] ?? null;

        if (!$competenciaId) {
            throw new Exception("ID de competencia no proporcionado.");
        }

        // Obtener la meta de ventas de la competencia
        $sqlCompetencia = "SELECT nombreCompetencia, metaVentas FROM competencias WHERE idCompetencia = :competenciaId AND activo = 1";
        $stmtCompetencia = $pdo->prepare($sqlCompetencia);
        $stmtCompetencia->execute(['competenciaId' => $competenciaId]);
        $competencia = $stmtCompetencia->fetch(PDO::FETCH_ASSOC);

        if (!$competencia) {
            throw new Exception("No se encontró la competencia.");
        }

        // Obtener las ventas acumuladas por equipo en la competencia seleccionada, incluyendo equipos sin ventas
        $sql = "SELECT e.idEquipo, e.nombreEquipo, ce.ventasAcumuladas
                FROM competencias_equipo ce
                JOIN equipos e ON ce.idEquipo = e.idEquipo
                WHERE ce.idCompetencia = :competenciaId AND ce.activo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['competenciaId' => $competenciaId]);
        $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar la información de metaVentas
        if ($equipos) {
            foreach ($equipos as &$equipo) {
                $equipo['metaVentas'] = $competencia['metaVentas'];
            }
            $response['status'] = true;
            $response['data'] = [
                'competencia' => $competencia,
                'equipos' => $equipos
            ];
        } else {
            $response['message'] = 'No se encontraron equipos en esta competencia.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Detalles de ventas por equipo y usuario
function detalleVentas()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $equipoId = $data['equipoId'] ?? null;

        if (!$equipoId) {
            throw new Exception("ID del equipo no proporcionado.");
        }
        $response['equipoId'] = $equipoId;


        $sql = "SELECT ventas.idCompetenciaEquipoVenta, ventas.monto, ventas.fechaVenta as fecha, usuarios.nombreUsuario AS nombreVendedor, ventas.estatus as estado
                FROM competencias_equipos_usuarios_ventas ventas
                JOIN equipo_usuarios eu ON ventas.idEquipoUsuario = eu.id
                JOIN usuarios ON eu.idUsuario = usuarios.idUsuario
                WHERE eu.idEquipo = :equipoId AND ventas.activo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['equipoId' => $equipoId]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($ventas) {
            $response['status'] = true;
            $response['data'] = $ventas;
        } else {
            $response['message'] = 'No se encontraron ventas para este equipo.';
            $response['data'] = $ventas;
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Detalles de ventas por equipo y usuario
function getVentasByEquipoComptencia()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);
    // return $data;die();
    try {
        // Validar que equipoId y competenciaId están presentes
        $equipoId = $data['equipoId'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;

        if (!$equipoId || !$competenciaId) {
            throw new Exception("ID del equipo y competencia son requeridos.");
        }

        // Consulta SQL para obtener las ventas del equipo en la competencia
        $sql = "SELECT ventas.idCompetenciaEquipoVenta, ventas.monto, ventas.fechaVenta, ventas.estatus as estado, 
                           usuarios.nombreUsuario AS nombreVendedor,usuarios.idUsuario,ventas.idEquipoUsuario as idIntegrante,ventas.idCompetenciaEquipoVenta as folio
                    FROM competencias_equipos_usuarios_ventas ventas
                    JOIN equipo_usuarios eu ON ventas.idEquipoUsuario = eu.id
                    JOIN usuarios ON eu.idUsuario = usuarios.idUsuario
                    WHERE ventas.idCompetencia = :competenciaId AND eu.idEquipo = :equipoId AND ventas.activo = 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'competenciaId' => $competenciaId,
            'equipoId' => $equipoId
        ]);

        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($ventas) {
            $response['status'] = true;
            $response['data'] = $ventas;
        } else {
            $response['message'] = 'No se encontraron ventas para este equipo en la competencia.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Guardar una nueva venta
function guardarVenta()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $equipoId = $data['equipoId'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;
        $monto = $data['monto'] ?? null;
        $fechaVenta = $data['fechaVenta'] ?? null;
        $estatus = $data['estatus'] ?? null;
        $idEquipoUsuario = $data['idIntegrante'] ?? null;

        if (!$equipoId || !$competenciaId || !$monto || !$fechaVenta || !$idEquipoUsuario ||  !$estatus) {
            throw new Exception("Datos incompletos para registrar la venta.");
        }

        // Insertar una nueva venta
        $sql = "INSERT INTO competencias_equipos_usuarios_ventas (idCompetencia, idEquipoUsuario, monto, fechaVenta, estatus, activo)
                VALUES (:competenciaId, :idEquipoUsuario, :monto, :fechaVenta,:estatus, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'competenciaId' => $competenciaId,
            'idEquipoUsuario' => $idEquipoUsuario,
            'monto' => $monto,
            'estatus' => $estatus,
            'fechaVenta' => $fechaVenta
        ]);

        // Verificar el estatus y actualizar ventas acumuladas
        if ($estatus == 2) {
            // Si el estatus es 2 (activo), sumar la venta a ventasAcumuladas
            $sqlUpdate = "UPDATE competencias_equipo SET ventasAcumuladas = ventasAcumuladas + :monto, fechaActualizacion = NOW() 
                          WHERE idCompetencia = :competenciaId AND idEquipo = :equipoId AND activo = 1";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'monto' => $monto,
                'competenciaId' => $competenciaId,
                'equipoId' => $equipoId
            ]);
        }

        $response['status'] = true;
        $response['message'] = 'Venta registrada correctamente.';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}


// Editar una venta existente
function editarVenta()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $idVenta = $data['idVenta'] ?? null;
        $monto = $data['monto'] ?? null;
        $estatus = $data['estatus'] ?? null;
        $fechaVenta = $data['fechaVenta'] ?? null;
        $idIntegrante = $data['idIntegrante'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;
        $equipoId = $data['equipoId'] ?? null;

        if (!$idVenta || !$monto || !$fechaVenta || !$idIntegrante) {
            throw new Exception("Datos incompletos para editar la venta.");
        }

        // Obtener el monto anterior y el estatus de la venta antes de actualizar
        $sqlGet = "SELECT monto, estatus FROM competencias_equipos_usuarios_ventas WHERE idCompetenciaEquipoVenta = :idVenta AND activo = 1";
        $stmtGet = $pdo->prepare($sqlGet);
        $stmtGet->execute(['idVenta' => $idVenta]);
        $ventaAnterior = $stmtGet->fetch(PDO::FETCH_ASSOC);

        // Actualizar la venta existente
        $sql = "UPDATE competencias_equipos_usuarios_ventas 
                SET monto = :monto, fechaVenta = :fechaVenta, idEquipoUsuario = :idEquipoUsuario, estatus = :estatus 
                WHERE idCompetenciaEquipoVenta = :idVenta AND activo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'monto' => $monto,
            'fechaVenta' => $fechaVenta,
            'estatus' => $estatus,
            'idEquipoUsuario' => $idIntegrante,
            'idVenta' => $idVenta
        ]);

        // Actualizar ventasAcumuladas si el estatus cambió
        if ($ventaAnterior && $ventaAnterior['estatus'] == 2) {
            // Si la venta anterior era activa (estatus 2), restarla de ventasAcumuladas
            $sqlUpdateRestar = "UPDATE competencias_equipo SET ventasAcumuladas = ventasAcumuladas - :montoAnterior, fechaActualizacion = NOW()
                                WHERE idCompetencia = :competenciaId AND idEquipo = :equipoId AND activo = 1";
            $stmtRestar = $pdo->prepare($sqlUpdateRestar);
            $stmtRestar->execute([
                'montoAnterior' => $ventaAnterior['monto'],
                'competenciaId' => $competenciaId,
                'equipoId' => $equipoId
            ]);
        }

        if ($estatus == 2) {
            // Si el nuevo estatus es activo (2), sumar el nuevo monto a ventasAcumuladas
            $sqlUpdateSumar = "UPDATE competencias_equipo SET ventasAcumuladas = ventasAcumuladas + :monto, fechaActualizacion = NOW()
                               WHERE idCompetencia = :competenciaId AND idEquipo = :equipoId AND activo = 1";
            $stmtSumar = $pdo->prepare($sqlUpdateSumar);
            $stmtSumar->execute([
                'monto' => $monto,
                'competenciaId' => $competenciaId,
                'equipoId' => $equipoId
            ]);
        }

        $response['status'] = true;
        $response['message'] = 'Venta actualizada correctamente.';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Eliminar una venta
function eliminarVenta()
{
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $idVenta = $data['idVenta'] ?? null;

        if (!$idVenta) {
            throw new Exception("ID de la venta no proporcionado.");
        }

        // Marcar la venta como inactiva en lugar de eliminarla físicamente
        $sql = "UPDATE competencias_equipos_usuarios_ventas SET activo = 0 WHERE idCompetenciaEquipoVenta = :idVenta";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idVenta' => $idVenta]);

        $response['status'] = true;
        $response['message'] = 'Venta eliminada correctamente.';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
function obtenerFraseRandom()
{
    $frases = [
        "Eso cuesta como si hubieras pedido prestado al futuro.",
        "Vas a necesitar una máquina del tiempo para pagarlo a plazos.",
        "Con ese precio, hasta mi tarjeta de crédito salió corriendo.",
        "Creo que hasta los bancos rechazarían financiar eso.",
        "Eso está a nivel 'vende todo y sigue debiendo'.",
        "Parece que quieren que done un pulmón para comprarlo.",
        "Con ese precio, hasta los ricos se lo pensarían dos veces.",
        "Eso tiene precio de museo privado, ¿o qué?",
        "Ni todos mis ahorros combinados alcanzarían para eso.",
        "Es más fácil encontrar oro que pagar eso.",
        "Con ese costo, siento que me están cobrando por soñar con él.",
        "Eso es como comprar la Torre Eiffel en cuotas.",
        "Vas a tener que trabajar dos vidas para costearlo.",
        "Eso cuesta como si viniera con su propia banda sonora.",
        "Creo que ni el premio de la lotería alcanza para eso.",
        "Con ese precio, mejor me hago monje y renuncio al materialismo.",
        "Eso cuesta como si estuviera bendecido por los dioses.",
        "Parece que están vendiendo una reliquia sagrada con eso.",
        "Con ese costo, prefiero adoptar un nuevo estilo de vida: sin deudas.",
        "Eso es más caro que la libertad misma.",
        "Parece que incluye un viaje VIP al pasado.",
        "Con ese precio, hasta mi sombra se sintió pobre.",
        "Eso está tan caro que la calculadora se bloqueó.",
        "Es como si cada billete que pagaras viniera con lágrimas incluidas.",
        "Eso cuesta como si estuvieras comprando un pedazo del sol.",
        "Con ese costo, mejor hago una startup para generar ingresos.",
        "Eso está a nivel 'vende todo y sigue debiendo'.",
        "Eso cuesta como si tuvieras que hipotecar hasta tus sueños.",
        "Es más barato contratar un genio de la lámpara que pagar eso.",
        "Con ese precio, prefiero aprender a fabricarlo yo mismo.",
        "Eso cuesta más que los libros de texto de una universidad de élite.",
        "Con ese precio, hasta el cajero automático tendría miedo.",
        "Eso está tan caro que parece diseñado para extraterrestres ricos.",
        "Con eso podría financiar una serie de 10 temporadas en HBO.",
        "Eso cuesta como si estuviera hecho de polvo de estrellas.",
        "Parece que están vendiendo una entrada VIP al cielo.",
        "Con ese precio, mejor lo admiro desde lejos.",
        "Eso cuesta como si incluyera una franquicia entera.",
        "Creo que con eso podría comprar el título de nobleza.",
        "Eso está más caro que el billete de ida al espacio.",
        "Eso cuesta como si estuvieras construyendo un imperio.",
        "Es tan caro que hasta mis nietos sentirán la deuda.",
        "Con ese precio, preferiría renunciar a todos mis caprichos.",
        "Parece que están cobrando hasta los sueños de grandeza.",
        "Con ese costo, hasta mi perro se siente pobre.",
        "Eso tiene precio de antigüedad perdida en el tiempo.",
        "Parece que incluye un castillo encantado con dragones.",
        "Eso está tan caro que parece una broma de mal gusto.",
        "Con ese precio, mejor me convierto en arqueólogo y lo busco gratis."
    ];

    $fraseAleatoria = $frases[array_rand($frases)];

    $response = [
        "status" => true,
        "title" => "Frase creativa sobre precios altos",
        "message" => $fraseAleatoria
    ];

    return ($response);
}
function calcularInflacionConDetalle()
{
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);
    try {
        $monto = $data['monto'] ?? null;
        $pais = $data['pais'] ?? null;

        // Datos de inflación
        $inflationData = [
            "México" => [5.0, 4.8, 4.5, 4.3, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 4.0, 4.1, 4.0, 4.2, 4.0, 4.1, 4.0, 4.1, 4.0, 4.2, 4.1, 4.0, 4.0, 4.1, 4.0, 4.2, 4.0, 4.1, 4.0, 4.0, 4.1, 4.0],
            "Estados Unidos" => [3.2, 2.9, 2.7, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 2.5, 2.6, 2.5, 2.7, 2.5, 2.6, 2.5, 2.7, 2.6, 2.5, 2.6, 2.5, 2.4, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7],
            "Canadá" => [2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 1.5, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.0, 2.1, 2.0, 1.9, 2.0, 1.9, 2.0, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7],
            "España" => [3.4, 3.2, 3.0, 2.8, 2.6, 2.5, 2.4, 2.3, 2.2, 2.1, 2.0, 2.1, 2.0, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.3, 2.2, 2.1, 2.0, 2.0, 1.9, 1.8],
            "Chile" => [4.8, 4.5, 4.3, 4.0, 3.8, 3.6, 3.4, 3.3, 3.2, 3.0, 3.5, 3.6, 3.4, 3.5, 3.3, 3.5, 3.6, 3.4, 3.5, 3.4, 3.6, 3.5, 3.4, 3.5, 3.6, 3.7, 3.5, 3.4, 3.3, 3.2, 3.1, 3.0],
            "Reino Unido" => [3.1, 2.9, 2.7, 2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 2.5, 2.6, 2.5, 2.6, 2.5, 2.7, 2.5, 2.6, 2.7, 2.5, 2.6, 2.5, 2.4, 2.5, 2.4, 2.5, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8],
            "Colombia" => [5.5, 5.3, 5.0, 4.8, 4.7, 4.5, 4.4, 4.2, 4.1, 4.0, 4.5, 4.6, 4.5, 4.7, 4.6, 4.8, 4.7, 4.8, 4.6, 4.5, 4.7, 4.5, 4.4, 4.3, 4.5, 4.6, 4.2, 4.1, 4.0, 4.0, 3.9, 3.8],
            "Argentina" => [10.0, 9.5, 9.0, 8.5, 8.0, 7.5, 7.0, 6.5, 6.0, 5.5, 8.0, 7.5, 7.0, 6.5, 6.0, 5.5, 5.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.0, 4.5, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8],
            "Brasil" => [6.0, 5.8, 5.5, 5.2, 5.0, 4.8, 4.6, 4.5, 4.3, 4.2, 5.0, 5.2, 5.1, 5.3, 5.2, 5.0, 4.9, 4.8, 4.7, 4.6, 4.5, 4.4, 4.3, 4.5, 4.6, 4.7, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0],
            "Portugal" => [2.6, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8, 1.7, 1.6, 2.0, 2.1, 2.2, 2.1, 2.0, 2.1, 2.2, 2.0, 2.1, 2.0, 2.1, 2.0, 2.1, 2.2, 2.3, 2.4, 2.3, 2.2, 2.1, 2.0, 1.9, 1.8],
            "República Dominicana" => [4.5, 4.3, 4.2, 4.1, 4.0, 3.9, 3.8, 3.7, 3.6, 3.5, 4.0, 4.1, 4.0, 4.2, 4.1, 4.0, 4.3, 4.2, 4.1, 4.0, 4.3, 4.4, 4.5, 4.6, 4.7, 4.8, 4.5, 4.4, 4.3, 4.2, 4.1, 4.0],
            "years" => [2024, 2025, 2026, 2027, 2028, 2029, 2030, 2031, 2032, 2033, 2034, 2035, 2036, 2037, 2038, 2039, 2040, 2041, 2042, 2043, 2044, 2045, 2046, 2047, 2048, 2049, 2050, 2051, 2052, 2053, 2054, 2055]
        ];
        

        // Verificar si el país existe en los datos
        if (!isset($inflationData[$pais])) {
            throw new Exception("País no encontrado en los datos de inflación. ",$pais);
        }

        // Obtener los datos necesarios
        $inflacionAnual = $inflationData[$pais];
        $years = $inflationData['years'];
        $montoActualizado = $monto;

        // Calcular el monto ajustado para cada año
        $montoConInflacion = [];
        foreach ($years as $index => $anio) {
            if (!isset($inflacionAnual[$index])) {
                throw new Exception("Datos de inflación incompletos para el año $anio.");
            }

            // Aplicar la inflación anual
            $montoActualizado *= (1 + $inflacionAnual[$index] / 100);

            // Guardar el resultado redondeado
            $montoConInflacion[$anio] = round($montoActualizado, 2);
        }
        $acumulado = [
            "2_anios" => array_sum(array_slice($montoConInflacion, 0, 2)),
            "5_anios" => array_sum(array_slice($montoConInflacion, 0, 5)),
            "10_anios" => array_sum(array_slice($montoConInflacion, 0, 10)),
            "15_anios" => array_sum(array_slice($montoConInflacion, 0, 15)),
            "20_anios" => array_sum(array_slice($montoConInflacion, 0, 20)),
        ];
        // Devolver el resultado en un array asociativo
        return [
            "status" => true,
            "message" => "Cálculo de inflación completado.",
            "data" => [
                "pais" => $pais,
                "monto_inicial" => $monto,
                "amountConInflacion" => $montoConInflacion,
                "acumulado" => $acumulado

            ]
        ];

    } catch (Exception $e) {
        // Manejo de errores
        return [
            "status" => false,
            "message" => $e->getMessage(),
            "data" => null
        ];
    }
}
