<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Asuncion');
require_once '../config.php'; // Asegúrate de llamar a las funciones de ayuda.

$response = ['status' => 'error', 'message' => 'Error inesperado'];

$id = $_POST['idRubro'] ?? '';
$idResponsable = $_POST['idb'];
$descripcion = $_POST['descripcion'] ?? '';

if ($descripcion === '' || empty($idResponsable)) {
    $response['message'] = 'Faltan campos obligatorios';
    echo json_encode($response);
    exit;
}

if ($id === '') {
    // INSERTAR
    $stmt = $link->prepare("INSERT INTO rubros (descripcion) VALUES (?)");
    $stmt->bind_param("s", $descripcion);
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Rubro registrado correctamente'];
    } else {
        $response['message'] = 'Error al insertar';
    }
} else {
    // ACTUALIZAR
    $stmt = $link->prepare("UPDATE rubros SET descripcion = ? WHERE id = ?");
    $stmt->bind_param("si", $descripcion, $id);
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Rubro actualizado correctamente'];
    } else {
        $response['message'] = 'Error al actualizar';
    }
}

echo json_encode($response);
