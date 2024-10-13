<?php
include '../../../database/db.php'; // Conexión a la base de datos

// Función para asociar usuarios a equipos
function asociarUsuarioEquipo() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $usuarioId = $data['usuarioId'] ?? null;
        $equipoId = $data['equipoId'] ?? null;

        if (!$usuarioId || !$equipoId) {
            throw new Exception("Datos incompletos para asociar usuario a equipo.");
        }

        // Verificar si el usuario ya está asociado a ese equipo
        $sqlCheck = "SELECT * FROM equipo_usuarios WHERE idUsuario = :idUsuario AND idEquipo = :idEquipo";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['idUsuario' => $usuarioId, 'idEquipo' => $equipoId]);
        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            throw new Exception("Este usuario ya está asociado a este equipo.");
        }

        // Insertar la relación en la tabla equipo_usuarios
        $sql = "INSERT INTO equipo_usuarios (idUsuario, idEquipo) VALUES (:idUsuario, :idEquipo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $usuarioId, 'idEquipo' => $equipoId]);

        $response['status'] = true;
        $response['message'] = 'Usuario asociado correctamente al equipo';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Función para asociar equipos a competencias
function asociarEquipoCompetencia() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $equipoId = $data['equipoId'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;

        if (!$equipoId || !$competenciaId) {
            throw new Exception("Datos incompletos para asociar equipo a competencia.");
        }

        // Verificar si el equipo ya está asociado a esa competencia
        $sqlCheck = "SELECT * FROM competencias_equipo WHERE idEquipo = :idEquipo AND idCompetencia = :idCompetencia";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute(['idEquipo' => $equipoId, 'idCompetencia' => $competenciaId]);
        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            throw new Exception("Este equipo ya está asociado a esta competencia.");
        }

        // Insertar la relación en la tabla competencias_equipo
        $sql = "INSERT INTO competencias_equipo (idEquipo, idCompetencia) VALUES (:idEquipo, :idCompetencia)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idEquipo' => $equipoId, 'idCompetencia' => $competenciaId]);

        $response['status'] = true;
        $response['message'] = 'Equipo asociado correctamente a la competencia';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
// Función para listar las asociaciones entre usuarios y equipos
function listarAsociacionesUsuarioEquipo() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT u.idUsuario, u.nombreUsuario, e.nombreEquipo 
                FROM equipo_usuarios eu 
                INNER JOIN usuarios u ON eu.idUsuario = u.idUsuario
                INNER JOIN equipos e ON eu.idEquipo = e.idEquipo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $asociaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response['status'] = true;
        $response['data'] = $asociaciones;
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Función para listar las asociaciones entre equipos y competencias
function listarAsociacionesEquipoCompetencia() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT e.nombreEquipo, c.nombreCompetencia 
                FROM competencias_equipo ce 
                INNER JOIN equipos e ON ce.idEquipo = e.idEquipo
                INNER JOIN competencias c ON ce.idCompetencia = c.idCompetencia";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $asociaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response['status'] = true;
        $response['data'] = $asociaciones;
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
