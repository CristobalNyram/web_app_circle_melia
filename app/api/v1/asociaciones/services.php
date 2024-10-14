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
        $sqlCheck = "
        SELECT * 
        FROM competencias_equipo 
        WHERE idEquipo = :idEquipo AND idCompetencia = :idCompetencia and competencias_equipo.activo = 1";
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
        $sql = "SELECT eu.id, u.idUsuario, u.nombreUsuario, e.idEquipo, e.nombreEquipo 
                FROM equipo_usuarios eu 
                INNER JOIN usuarios u ON u.activo = 1 AND eu.idUsuario = u.idUsuario
                INNER JOIN equipos e ON e.activo = 1 AND eu.idEquipo = e.idEquipo
                WHERE eu.activo = 1";
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
        $sql = "SELECT ce.id, e.idEquipo, e.nombreEquipo, c.idCompetencia, c.nombreCompetencia 
                FROM competencias_equipo ce 
                INNER JOIN equipos e ON e.activo = 1 AND ce.idEquipo = e.idEquipo
                INNER JOIN competencias c ON c.activo = 1 AND ce.idCompetencia = c.idCompetencia
                WHERE ce.activo = 1";
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

// Función para editar la asociación de usuario a equipo
function editarUsuarioEquipo() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $folio = intval($data['folio']) ?? null;
        $usuarioId = $data['usuarioId'] ?? null;
        $equipoId = $data['equipoId'] ?? null;
        $nuevoUsuarioId = $data['nuevoUsuarioId'] ?? null;
        $nuevoEquipoId = $data['nuevoEquipoId'] ?? null;

        if (!$usuarioId || !$equipoId || !$nuevoUsuarioId || !$nuevoEquipoId || !$folio) {
            throw new Exception("Datos incompletos para editar la asociación.");
        }

        // Verificar si el nuevo usuario ya está asociado al equipo
        $sqlCheck = "SELECT * FROM equipo_usuarios WHERE idUsuario = :nuevoUsuarioId AND idEquipo = :nuevoEquipoId AND activo = 1";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            'nuevoUsuarioId' => $nuevoUsuarioId,
            'nuevoEquipoId' => $nuevoEquipoId
        ]);

        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            throw new Exception("El usuario ya está asociado a este equipo.");
        }

        // Actualizar la relación en la tabla equipo_usuarios
        $sql = "UPDATE equipo_usuarios SET idUsuario = :nuevoUsuarioId, idEquipo = :nuevoEquipoId 
                WHERE idUsuario = :usuarioId AND idEquipo = :equipoId AND id = :id AND activo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nuevoUsuarioId' => $nuevoUsuarioId,
            'nuevoEquipoId' => $nuevoEquipoId,
            'usuarioId' => $usuarioId,
            'equipoId' => $equipoId,
            'id' => $folio
        ]);

        $response['status'] = true;
        $response['message'] = 'Asociación actualizada correctamente';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

// Función para editar la asociación de equipo a competencia
function editarEquipoCompetencia() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $folio = $data['folio'] ?? null;
        $equipoId = $data['equipoId'] ?? null;
        $competenciaId = $data['competenciaId'] ?? null;
        $nuevoEquipoId = $data['nuevoEquipoId'] ?? null;
        $nuevoCompetenciaId = $data['nuevoCompetenciaId'] ?? null;

        if (!$equipoId || !$competenciaId || !$nuevoEquipoId || !$nuevoCompetenciaId || !$folio) {
            throw new Exception("Datos incompletos para editar la asociación.");
        }

        // Verificar si el nuevo equipo ya está asociado a la nueva competencia
        $sqlCheck = "SELECT * FROM competencias_equipo WHERE idEquipo = :nuevoEquipoId AND idCompetencia = :nuevoCompetenciaId AND activo = 1";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            'nuevoEquipoId' => $nuevoEquipoId,
            'nuevoCompetenciaId' => $nuevoCompetenciaId
        ]);

        $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            throw new Exception("El equipo ya está asociado a esta competencia.");
        }

        // Actualizar la relación en la tabla competencias_equipo
        $sql = "UPDATE competencias_equipo SET idEquipo = :nuevoEquipoId, idCompetencia = :nuevoCompetenciaId 
                WHERE idEquipo = :equipoId AND idCompetencia = :competenciaId AND id = :folio AND activo = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nuevoEquipoId' => $nuevoEquipoId,
            'nuevoCompetenciaId' => $nuevoCompetenciaId,
            'equipoId' => $equipoId,
            'folio' => $folio,
            'competenciaId' => $competenciaId
        ]);

        $response['status'] = true;
        $response['message'] = 'Asociación actualizada correctamente';
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

