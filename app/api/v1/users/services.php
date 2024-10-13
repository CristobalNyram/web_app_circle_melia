<?php
include 'db.php'; // Conexión a la base de datos

function saveUser($data) {
    global $pdo;
    $response = ['status' => false, 'message' => ''];

    try {
        $pdo->beginTransaction();
        
        $nombreUsuario = $data['nombreUsuario'];
        $tipo = $data['tipo'];
        $idUsuario = $data['idUsuario'];

        if (empty($idUsuario)) {
            // Crear nuevo usuario
            $sql = "INSERT INTO usuarios (nombreUsuario, tipo) VALUES (:nombreUsuario, :tipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreUsuario' => $nombreUsuario, 'tipo' => $tipo]);
            $response['message'] = 'Usuario creado correctamente';
        } else {
            // Editar usuario existente
            $sql = "UPDATE usuarios SET nombreUsuario = :nombreUsuario, tipo = :tipo WHERE idUsuario = :idUsuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['nombreUsuario' => $nombreUsuario, 'tipo' => $tipo, 'idUsuario' => $idUsuario]);
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
        $response['data'] = $users
