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
