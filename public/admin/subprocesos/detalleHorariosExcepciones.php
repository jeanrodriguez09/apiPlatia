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

$sql = "SELECT id, fecha, motivo 
        FROM itemHorarioExcepciones 
        WHERE estado = 1 AND idItem = $idItem 
        ORDER BY fecha ASC";

$query = $link->query($sql);

if (!$query) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la consulta SQL: ' . mysql_error()
    ]);
    exit;
}

while ($row = mysqli_fetch_assoc($query)) {
    // Convertimos fecha para mostrar más bonito si hace falta en frontend
    $row['fechaFormateada'] = date("d/m/Y", strtotime($row['fecha']));
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Excepciones obtenidas correctamente',
    'data' => $data
]);
