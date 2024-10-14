<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'services.php'; // Archivo con las funciones para equipos
$response = ['status' => false, 'message' => '', 'data' => []];

try {
    // Detectar la acción a ejecutar
    $action = $_REQUEST['action'] ?? '';

    switch ($action) {
        case 'save':
            $response = saveTeam();
            break;

        case 'get':
            $idEquipo = $_REQUEST['idEquipo'] ?? null;
            if ($idEquipo) {
                $response = getTeam($idEquipo);
            } else {
                $response['message'] = 'ID del equipo no proporcionado';
            }
            break;

        case 'delete':
            $idEquipo = $_REQUEST['idEquipo'] ?? null;
            if ($idEquipo) {
                $response = deleteTeam($idEquipo);
            } else {
                $response['message'] = 'ID del equipo no proporcionado';
            }
            break;

        case 'list':
            $response = listTeams();
            break;

        case 'listIntegrantesEquipo': // Nueva acción para listar los integrantes del equipo
            $response = listIntegrantesEquipo();
            break;
        default:
            $response['message'] = 'Acción no válida';
    }
} catch (Exception $e) {
    $response['message'] = 'Error general: ' . $e->getMessage();
}

echo json_encode($response);
