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

        // Obtener las ventas acumuladas por equipo y usuario en la competencia seleccionada
        $sql = "SELECT eu.id as idEquipoUsuario, e.nombreEquipo, u.nombreUsuario, v.monto, v.fechaVenta as fecha, v.estatus as estado
                FROM competencias_equipos_usuarios_ventas v
                JOIN equipo_usuarios eu ON v.idEquipoUsuario = eu.id
                JOIN equipos e ON eu.idEquipo = e.idEquipo
                JOIN usuarios u ON eu.idUsuario = u.idUsuario
                WHERE v.idCompetencia = :competenciaId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['competenciaId' => $competenciaId]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($ventas) {
            $response['status'] = true;
            $response['data'] = $ventas;
        } else {
            $response['message'] = 'No se encontraron ventas para esta competencia.';
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

        // Obtener los detalles de las ventas de un equipo en específico
        $sql = "SELECT ventas.idVenta, ventas.monto, ventas.fechaVenta as fecha, usuarios.nombreUsuario AS nombreVendedor, ventas.estatus as estado
                FROM ventas
                JOIN usuarios ON ventas.idUsuario = usuarios.idUsuario
                WHERE ventas.idEquipo = :equipoId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['equipoId' => $equipoId]);
        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($ventas) {
            $response['status'] = true;
            $response['data'] = $ventas;
        } else {
            $response['message'] = 'No se encontraron ventas para este equipo.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
