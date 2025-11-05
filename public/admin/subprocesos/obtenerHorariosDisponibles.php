<?php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // Conexión a la base de datos
header('Content-Type: application/json');

// Validar parámetros
if (empty($_POST['idServicio']) || empty($_POST['fecha'])) {
    echo json_encode([
        'status' => 'warning',
        'message' => 'No se recibieron los parámetros necesarios'
    ]);
    exit;
}

$idServicio = intval($_POST['idServicio']);
$fecha = $_POST['fecha']; // Esperamos formato YYYY-MM-DD

// Consulta horarios disponibles para la fecha y servicio
$sql = "SELECT id AS idAgenda, horaInicio, horaFin
        FROM agendaDisponible
        WHERE estado = 1 
          AND idItem = $idServicio
          AND DATE(fecha) = '$fecha'
        ORDER BY horaInicio ASC";

$query = $link->query($sql);

if (!$query) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la consulta SQL: ' . mysqli_error($link)
    ]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Horarios disponibles obtenidos correctamente',
    'data' => $data
]);
