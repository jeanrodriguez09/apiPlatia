<?php
require_once '../config.php';

header('Content-Type: application/json');

if (!isset($_POST['idEmpresa'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró idEmpresa en la sesión']);
    exit;
}

$idEmpresa = $_POST['idEmpresa'];

try {
    $sql = "SELECT id AS idServicio, nombre AS nombreServicio 
            FROM items 
            WHERE idEmpresa = ? AND estado = 1
            ORDER BY nombre ASC";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $servicios]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener servicios']);
}
