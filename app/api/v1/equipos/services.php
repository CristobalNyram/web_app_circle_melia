<?php
include '../../../database/db.php'; // Conexión a la base de datos

function saveTeam() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    // Decodificar los datos enviados por el cliente
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $pdo->beginTransaction();

        $nombreEquipo = $data['nombreEquipo'] ?? null;
        $password = $data['password'] ?? null;
        $idEquipo = $data['equipoId'] ?? null;

        if (!$nombreEquipo || !$password) {
            throw new Exception("Datos incompletos para crear o actualizar el equipo.");
        }

        if (empty($idEquipo)) {
            // Crear nuevo equipo
            $sql = "INSERT INTO equipos (nombreEquipo, password) VALUES (:nombreEquipo, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreEquipo' => $nombreEquipo, 'password' => $password]);
            $response['message'] = 'Equipo creado correctamente';
        } else {
            // Editar equipo existente
            $sql = "UPDATE equipos SET nombreEquipo = :nombreEquipo, password = :password WHERE idEquipo = :idEquipo";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreEquipo' => $nombreEquipo, 'password' => $password, 'idEquipo' => $idEquipo]);
            $response['message'] = 'Equipo actualizado correctamente';
        }

        $pdo->commit();
        $response['status'] = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function getTeam($idEquipo) {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM equipos WHERE idEquipo = :idEquipo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idEquipo' => $idEquipo]);

        $team = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($team) {
            $response['status'] = true;
            $response['data'] = $team;
        } else {
            $response['message'] = 'Equipo no encontrado';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function deleteTeam($idEquipo) {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    try {
        $pdo->beginTransaction();

        $sql = "DELETE FROM equipos WHERE idEquipo = :idEquipo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idEquipo' => $idEquipo]);

        $pdo->commit();
        $response['status'] = true;
        $response['message'] = 'Equipo eliminado correctamente';
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function listTeams() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM equipos";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response['status'] = true;
        $response['data'] = $teams;
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}