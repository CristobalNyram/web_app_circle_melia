<?php
include '../../../database/db.php'; // Conexión a la base de datos
$session_lifetime = 2400; // 40 minutos

// Configurar la duración de la cookie y el tiempo de vida de la sesión
ini_set('session.gc_maxlifetime', $session_lifetime);
ini_set('session.cookie_lifetime', $session_lifetime);

// Función para asociar usuarios a equipos
function loginAdmin()
{
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

        $usuarioTypeValid = ['invitado', 'admin'];
        if (isset($usuario['tipo']) && $usuario['tipo'] !== null && $usuario['tipo'] !== '' && !in_array($usuario['tipo'], $usuarioTypeValid)) {
            throw new Exception("Tipo de usuario incorrecto.");
        }

        // Si todo está correcto, iniciar la sesión y guardar los datos
        $_SESSION['nickname'] = $nickname;
        $_SESSION['tipo'] = $usuario['tipo'];
        $_SESSION['last_activity'] = time(); // Inicializar la última actividad

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

function loginEquipo()
{
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
        $_SESSION['last_activity'] = time(); // Inicializar la última actividad

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

function checkSessionStatus()
{
    session_start();
    $response = ['status' => false, 'message' => '', 'time_remaining' => 0];

    // Calcular el tiempo de inactividad y el tiempo restante de la sesión
    if (isset($_SESSION['tipo']) && !empty($_SESSION['tipo']) && isset($_SESSION['last_activity'])) {
        $time_elapsed = time() - $_SESSION['last_activity'];
        $time_remaining = $GLOBALS['session_lifetime'] - $time_elapsed;

        if ($time_remaining > 0) {
            // Si la sesión sigue activa, actualizar 'last_activity' y devolver el tiempo restante
           # $_SESSION['last_activity'] = time(); // Actualizar la última actividad
            $response['status'] = true;
            $response['message'] = 'La sesión sigue activa.';
            $response['time_remaining'] = $time_remaining;
            $minutes = floor($time_remaining / 60);
            $seconds = $time_remaining % 60;
            // Formatear el tiempo restante en un formato legible
            $formatted_time_remaining = sprintf('%d minutos y %d segundos', $minutes, $seconds);
            $response['formatted_time_remaining'] = $formatted_time_remaining;

        } else {
            // Si el tiempo ha expirado, destruir la sesión
            $response['message'] = 'Su sesión ha expirado.';
            session_unset();
            session_destroy();
        }
    } else {
        $response['message'] = 'No se encontró una sesión activa o el tipo de usuario no está definido.';
        session_unset();
        session_destroy();
    }

    return ($response);
}
