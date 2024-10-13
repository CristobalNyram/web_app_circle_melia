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
        case 'listVentasCompetencia':
            $response = listVentasCompetencia();
            break;

        case 'detalleVentas':
            $response = detalleVentas();
            break;

        default:
            $response['message'] = 'Acción no válida';
    }

} catch (Exception $e) {
    $response['message'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
