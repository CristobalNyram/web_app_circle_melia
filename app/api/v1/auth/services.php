<?php
include '../../../database/db.php'; // Conexión a la base de datos

// Función para asociar usuarios a equipos
function loginAdmin() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Obtener los datos de entrada
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $nickname = $data['nickname'] ?? null;
        $contrasenia = $data['contrasenia'] ?? null;

        if (!$nickname || !$contrasenia) {
            throw new Exception("Datos incompletos para iniciar sesión.");
        }

        // Buscar al usuario por el nickname
        $sql = "SELECT idUsuario, nombreUsuario, tipo, contrasenia, activo 
                FROM usuarios 
                WHERE nickname = :nickname";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['nickname' => $nickname]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            throw new Exception("El usuario no existe.");
        }

        // Verificar si el usuario está activo
        if ($usuario['activo'] != 1) {
            throw new Exception("La cuenta del usuario está desactivada.");
        }

        // Verificar la contraseña sin encriptación
        if ($contrasenia !== $usuario['contrasenia']) {
            throw new Exception("La contraseña es incorrecta.");
        }

        // Si todo está correcto, iniciar la sesión y guardar los datos
        $_SESSION['nickname'] = $nickname;
        $_SESSION['tipo'] = $usuario['tipo'];

        // Retornar éxito y datos del usuario
        $response['status'] = true;
        $response['message'] = "Inicio de sesión exitoso.";
        $response['data'] = [
            'idUsuario' => $usuario['idUsuario'],
            'nombreUsuario' => $usuario['nombreUsuario'],
            'tipo' => $usuario['tipo']
        ];

    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}

function loginEquipo() {
    global $pdo;
    $response = ['status' => false, 'message' => '', 'data' => []];

    // Iniciar la sesión de PHP
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Obtener los datos de entrada
    $data = json_decode(file_get_contents("php://input"), true);

    try {
        $equipoId = $data['equipoId'] ?? null;
        $contrasenia = $data['contrasenia'] ?? null;

        if (!$equipoId || !$contrasenia) {
            throw new Exception("Datos incompletos para iniciar sesión.");
        }

        // Buscar el equipo por el id
        $sql = "SELECT idEquipo, nombreEquipo, password 
                FROM equipos 
                WHERE idEquipo = :equipoId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['equipoId' => $equipoId]);
        $equipo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$equipo) {
            throw new Exception("El equipo no existe.");
        }

        // Verificar la contraseña
        if ($contrasenia !== $equipo['password']) {
            throw new Exception("La contraseña es incorrecta.");
        }

        // Si todo está correcto, guardar la sesión
        $_SESSION['equipoId'] = $equipo['idEquipo'];
        $_SESSION['nombreEquipo'] = $equipo['nombreEquipo'];
        $_SESSION['tipo'] = 'equipo';  // Establecer el tipo como 'equipo'

        // Retornar éxito
        $response['status'] = true;
        $response['message'] = "Inicio de sesión exitoso.";
        $response['data'] = [
            'idEquipo' => $equipo['idEquipo'],
            'nombreEquipo' => $equipo['nombreEquipo']
        ];

    } catch (Exception $e) {
        $response['message'] = 'Error: ' . $e->getMessage();
    }

    return $response;
}


