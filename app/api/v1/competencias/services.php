<?php
include '../../../database/db.php'; // Conexión a la base de datos

function saveCompetencia() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    // Decodificar los datos enviados por el cliente
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $pdo->beginTransaction();

        $nombreCompetencia = $data['nombreCompetencia'] ?? null;
        $metaVentas = $data['metaVentas'] ?? null;
        $fechaInicio = $data['fechaInicio'] ?? null;
        $fechaFin = $data['fechaFin'] ?? null;
        $idCompetencia = $data['competenciaId'] ?? null;

        if (!$nombreCompetencia || !$metaVentas || !$fechaInicio || !$fechaFin) {
            throw new Exception("Datos incompletos para crear o actualizar la competencia.");
        }

        if (empty($idCompetencia)) {
            // Crear nueva competencia
            $sql = "INSERT INTO competencias (nombreCompetencia, metaVentas, fechaInicio, fechaFin) VALUES (:nombreCompetencia, :metaVentas, :fechaInicio, :fechaFin)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreCompetencia' => $nombreCompetencia, 'metaVentas' => $metaVentas, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin]);
            $response['message'] = 'Competencia creada correctamente';
        } else {
            // Editar competencia existente
            $sql = "UPDATE competencias SET nombreCompetencia = :nombreCompetencia, metaVentas = :metaVentas, fechaInicio = :fechaInicio, fechaFin = :fechaFin WHERE idCompetencia = :idCompetencia";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreCompetencia' => $nombreCompetencia, 'metaVentas' => $metaVentas, 'fechaInicio' => $fechaInicio, 'fechaFin' => $fechaFin, 'idCompetencia' => $idCompetencia]);
            $response['message'] = 'Competencia actualizada correctamente';
        }

        $pdo->commit();
        $response['status'] = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function getCompetencia($idCompetencia) {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM competencias WHERE idCompetencia = :idCompetencia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idCompetencia' => $idCompetencia]);

        $competencia = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($competencia) {
            $response['status'] = true;
            $response['data'] = $competencia;
        } else {
            $response['message'] = 'Competencia no encontrada';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function deleteCompetencia($idCompetencia) {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    try {
        $pdo->beginTransaction();

        $sql = "DELETE FROM competencias WHERE idCompetencia = :idCompetencia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idCompetencia' => $idCompetencia]);

        $pdo->commit();
        $response['status'] = true;
        $response['message'] = 'Competencia eliminada correctamente';
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function listCompetencias() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM competencias";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $competencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response['status'] = true;
        $response['data'] = $competencias;
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
function listCompetenciasEquipo() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    // Obtener los datos de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        $equipoId = $data['equipoId'] ?? null;

        if (!$equipoId) {
            throw new Exception("El ID del equipo es requerido.");
        }

        // Consultar las competencias a las que está inscrito el equipo
        $sql = "
            SELECT c.idCompetencia, c.nombreCompetencia, c.metaVentas
            FROM competencias c
            JOIN competencias_equipo ce ON ce.idCompetencia = c.idCompetencia
            WHERE ce.idEquipo = :equipoId AND ce.activo = 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['equipoId' => $equipoId]);

        $competencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($competencias) {
            $response['status'] = true;
            $response['data'] = $competencias;
        } else {
            throw new Exception("El equipo no tiene competencias inscritas.");
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return ($response);
}
