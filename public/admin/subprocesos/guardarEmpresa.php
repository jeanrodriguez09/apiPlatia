<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Asuncion');
require_once '../config.php'; // Asegúrate de llamar a las funciones de ayuda.

$response = ['status' => 'error', 'message' => 'Error inesperado'];

$id = isset($_POST['idEmpresa']) ? (int)$_POST['idEmpresa'] : 0;
$idResponsable = isset($_POST['idb']) ? (int)$_POST['idb'] : 0;
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$idRubro = $_POST['idRubro'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$reglasBasicas = $_POST['reglasBasicas'] ?? '';
$reglasRestrictivas = $_POST['reglasRestrictivas'] ?? '';

if ($nombre === '' || $correo === '' || empty($idRubro) || $idResponsable === 0) {
    $response['message'] = 'Faltan campos obligatorios';
    echo json_encode($response);
    exit;
}

if ($id === 0) {
    // INSERTAR
    $stmt = $link->prepare("INSERT INTO empresas (nombre, email_contacto, descripcionNegocio, idRubro, idUsuarioResponsable, reglasBasicas, reglasRestrictivas) VALUES (?, ?, ?, ?, ?, ?. ?)");
    $stmt->bind_param("sssiiss", $nombre, $correo, $descripcion, $idRubro, $idResponsable, $reglasBasicas. $reglasRestrictivas);
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Empresa registrada correctamente'];
    } else {
        $response['message'] = 'Error al insertar';
    }
} else {
    // ACTUALIZAR
    $stmt = $link->prepare("UPDATE empresas SET nombre = ?, email_contacto = ?, descripcionNegocio = ?, idRubro = ?, reglasBasicas = ?, reglasRestrictivas = ? WHERE id = ?");
    $stmt->bind_param("sssissi", $nombre, $correo, $descripcion, $idRubro, $reglasBasicas, $reglasRestrictivas, $id);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response = ['status' => 'success', 'message' => 'Empresa actualizada correctamente'];
        } else {
            $response['message'] = 'No se realizaron cambios o el ID no existe';
        }
    } else {
        $response['message'] = 'Error al actualizar';
    }
}

echo json_encode($response);
