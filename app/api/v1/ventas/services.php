<?php
include '../../../database/db.php'; // Conexión a la base de datos

// Listar las ventas por competencia
function listVentasCompetencia() {
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
function detalleVentas() {
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
function getVentasByEquipoComptencia() {
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
function guardarVenta() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $equipoId = $data['equipoId'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;
        $monto = $data['monto'] ?? null;
        $fechaVenta = $data['fechaVenta'] ?? null;
        $idEquipoUsuario = $data['idIntegrante'] ?? null;

        if (!$equipoId || !$competenciaId || !$monto || !$fechaVenta || !$idEquipoUsuario) {
            throw new Exception("Datos incompletos para registrar la venta.");
        }

        // Insertar una nueva venta
        $sql = "INSERT INTO competencias_equipos_usuarios_ventas (idCompetencia, idEquipoUsuario, monto, fechaVenta, estatus, activo)
                VALUES (:competenciaId, :idEquipoUsuario, :monto, :fechaVenta, 1, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'competenciaId' => $competenciaId,
            'idEquipoUsuario' => $idEquipoUsuario,
            'monto' => $monto,
            'fechaVenta' => $fechaVenta
        ]);

        // Verificar el estatus y actualizar ventas acumuladas
        if ($data['estatus'] == 2) {
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
function editarVenta() {
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
function eliminarVenta() {
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
