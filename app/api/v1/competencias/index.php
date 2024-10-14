<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'services.php'; // Archivo con las funciones para competencias
$response = ['status' => false, 'message' => '', 'data' => []];

try {
    // Detectar la acción a ejecutar
    $action = $_REQUEST['action'] ?? '';

    switch ($action) {
        case 'save':
            $response = saveCompetencia();
            break;
        case 'listCompetenciasEquipo':
            $response = listCompetenciasEquipo();
            break;
        case 'get':
            $idCompetencia = $_REQUEST['idCompetencia'] ?? null;
            if ($idCompetencia) {
                $response = getCompetencia($idCompetencia);
            } else {
                $response['message'] = 'ID de la competencia no proporcionado';
            }
            break;

        case 'delete':
            $idCompetencia = $_REQUEST['idCompetencia'] ?? null;
            if ($idCompetencia) {
                $response = deleteCompetencia($idCompetencia);
            } else {
                $response['message'] = 'ID de la competencia no proporcionado';
            }
            break;

        case 'list':
            $response = listCompetencias();
            break;
        case 'listVentasCompetencia':
            $response = listVentasCompetencia();
            break;

        default:
            $response['message'] = 'Acción no válida';
    }

} catch (Exception $e) {
    $response['message'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
