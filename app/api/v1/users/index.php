<?php
include 'services.php'; // Archivo con las funciones

$response = ['status' => false, 'message' => '', 'data' => []];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'];

        switch ($action) {
            case 'save':
                $response = saveUser($_POST);
                break;

            case 'get':
                $response = getUser($_POST['idUsuario']);
                break;

            case 'delete':
                $response = deleteUser($_POST['idUsuario']);
                break;

            case 'list':
                $response = listUsers();
                break;

            default:
                $response['message'] = 'Acción no válida';
        }
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
