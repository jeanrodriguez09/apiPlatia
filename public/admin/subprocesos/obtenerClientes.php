<?php
require_once '../config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_POST['idEmpresa'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se encontró idEmpresa en la sesión']);
    exit;
}

$idEmpresa = $_POST['idEmpresa'];

try {
    $sql = "SELECT id, nombre AS nombreCompleto 
            FROM clientes 
            WHERE idEmpresa = ?
            ORDER BY nombre ASC";
    $stmt = $link->prepare($sql);
    $stmt->bind_param("i", $idEmpresa);
    $stmt->execute();
    $result = $stmt->get_result();

    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $clientes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener clientes']);
}
