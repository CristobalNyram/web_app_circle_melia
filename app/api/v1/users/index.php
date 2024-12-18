<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'services.php'; // Archivo con las funciones
$response = ['status' => false, 'message' => '', 'data' => []];

try {
    // Detectar la acción a ejecutar
    $action = $_REQUEST['action'] ?? '';

    switch ($action) {
        case 'save':
            $response = saveUser();
            break;

        case 'get':
            $idUsuario = $_REQUEST['idUsuario'] ?? null;
            if ($idUsuario) {
                $response = getUser($idUsuario);
            } else {
                $response['message'] = 'ID de usuario no proporcionado';
            }
            break;

        case 'delete':
            $idUsuario = $_REQUEST['idUsuario'] ?? null;
            if ($idUsuario) {
                $response = deleteUser($idUsuario);
            } else {
                $response['message'] = 'ID de usuario no proporcionado';
            }
            break;

        case 'list':
            $response = listUsers();
            break;

        default:
            $response['message'] = 'Acción no válida';
    }

} catch (Exception $e) {
    $response['message'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
