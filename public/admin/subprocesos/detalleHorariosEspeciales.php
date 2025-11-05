<?php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // conexión mysql_connect
header('Content-Type: application/json');

if (!isset($_POST['idItem']) || empty($_POST['idItem'])) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'No se recibió el ID del servicio (idItem)'
    ]);
    exit;
}

$idItem = intval($_POST['idItem']);
$data = [];

// Listar horarios especiales
$sql = "SELECT id, fecha, horaInicio, horaFin, cupos 
        FROM itemsHorariosExcepcionales 
        WHERE estado = 1 AND idItem = $idItem 
        ORDER BY fecha ASC, horaInicio ASC";

$query = $link->query($sql);

if (!$query) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la consulta SQL: ' . mysql_error()
    ]);
    exit;
}

while ($row = mysqli_fetch_assoc($query)) {
    $row['fechaFormateada'] = date("d/m/Y", strtotime($row['fecha']));
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Horarios especiales obtenidos correctamente',
    'data' => $data
]);
