<?php
include '../../../database/db.php'; // Conexión a la base de datos

function saveUser() {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    // Decodificar los datos enviados por el cliente
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $pdo->beginTransaction();

        $nombreUsuario = $data['nombreUsuario'] ?? null;
        $tipo = $data['tipo'] ?? null;
        $idUsuario = $data['usuarioId'] ?? null;
        $nickname = $data['nickname'] ?? null;
        $contrasenia = $data['contrasenia'] ?? null;

        if (!$nombreUsuario || !$tipo) {
            throw new Exception("Datos incompletos para crear o actualizar el usuario.");
        }

        // Eliminar espacios y caracteres especiales del nickname
        if ($nickname) {
            $nickname = preg_replace('/[^A-Za-z0-9]/', '', $nickname); // Mantiene solo letras y números
        }

        // Eliminar espacios y caracteres especiales de la contraseña
        if ($contrasenia) {
            $contrasenia = preg_replace('/[^A-Za-z0-9]/', '', $contrasenia); // Mantiene solo letras y números
        }

        // Validar que el nickname no esté en uso por otro usuario
        if ($nickname) {
            $sqlCheckNickname = "SELECT idUsuario FROM usuarios WHERE nickname = :nickname AND idUsuario != :idUsuario";
            $stmtCheckNickname = $pdo->prepare($sqlCheckNickname);
            $stmtCheckNickname->execute([
                'nickname' => $nickname,
                'idUsuario' => $idUsuario ?? 0
            ]);
            $existingNickname = $stmtCheckNickname->fetch(PDO::FETCH_ASSOC);

            if ($existingNickname) {
                throw new Exception("El nickname ya está en uso, por favor elige otro.");
            }
        }

        if (empty($idUsuario)) {
            // Crear nuevo usuario
            $sql = "INSERT INTO usuarios (nombreUsuario, tipo, nickname, contrasenia) VALUES (:nombreUsuario, :tipo, :nickname, :contrasenia)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nombreUsuario' => $nombreUsuario,
                'tipo' => $tipo,
                'nickname' => $nickname,
                'contrasenia' => $contrasenia
            ]);
            $response['message'] = 'Usuario creado correctamente';
        } else {
            // Editar usuario existente
            $sql = "UPDATE usuarios SET nombreUsuario = :nombreUsuario, tipo = :tipo, nickname = :nickname, contrasenia = :contrasenia WHERE idUsuario = :idUsuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nombreUsuario' => $nombreUsuario,
                'tipo' => $tipo,
                'nickname' => $nickname,
                'contrasenia' => $contrasenia,
                'idUsuario' => $idUsuario
            ]);
            $response['message'] = 'Usuario actualizado correctamente';
        }

        $pdo->commit();
        $response['status'] = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function getUser($idUsuario) {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM usuarios WHERE idUsuario = :idUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $idUsuario]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $response['status'] = true;
            $response['data'] = $user;
        } else {
            $response['message'] = 'Usuario no encontrado';
        }
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function deleteUser($idUsuario) {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    try {
        $pdo->beginTransaction();

        $sql = "DELETE FROM usuarios WHERE idUsuario = :idUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['idUsuario' => $idUsuario]);

        $pdo->commit();
        $response['status'] = true;
        $response['message'] = 'Usuario eliminado correctamente';
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function listUsers() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    try {
        $sql = "SELECT * FROM usuarios";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $response['status'] = true;
        $response['data'] = $users;
    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}
