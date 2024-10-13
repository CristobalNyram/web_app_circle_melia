<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'services.php'; // Archivo con las funciones para asociaciones
$response = ['status' => false, 'message' => '', 'data' => []];

try {
    // Detectar la acción a ejecutar
    $action = $_REQUEST['action'] ?? '';

    switch ($action) {
        case 'asociarUsuarioEquipo':
            $response = asociarUsuarioEquipo();
            break;

        case 'asociarEquipoCompetencia':
            $response = asociarEquipoCompetencia();
            break;

        case 'listAsociacionesUsuarioEquipo':
            $response = listarAsociacionesUsuarioEquipo();
            break;

        case 'listAsociacionesEquipoCompetencia':
            $response = listarAsociacionesEquipoCompetencia();
            break;

        default:
            $response['message'] = 'Acción no válida';
    }

} catch (Exception $e) {
    $response['message'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
