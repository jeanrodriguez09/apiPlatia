<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Asuncion');

require_once '../config.php'; // Asegúrate de llamar a las funciones de ayuda.

// Verifica la conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

if (!isset($_POST['idEmpresa'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de la empresa no proporcionado.']);
    exit;
}

$id = intval($_POST['idEmpresa']); // Recibe el ID de la solicitud

// Consulta para obtener los datos de la solicitud
$query = "SELECT * FROM empresas WHERE id = $id"; // Asegúrate de ajustar la tabla y campos según tu estructura
$result = $link->query($query);

if ($result && mysqli_num_rows($result) > 0) {
    $solicitud = mysqli_fetch_assoc($result);
    echo json_encode(['status' => 'success', 'data' => $solicitud]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Empresa no encontrado.']);
}

$link->close();
?>