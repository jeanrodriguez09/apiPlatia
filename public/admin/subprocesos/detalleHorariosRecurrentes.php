<?php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // Contiene la conexión con mysql_connect
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

// Consulta horarios recurrentes del servicio
$sql = "SELECT id, dia, horaInicio, horaFin, cupos 
        FROM itemHorarioRecurrente 
        WHERE estado = 1 AND idItem = $idItem 
        ORDER BY dia ASC, horaInicio ASC";

$query = $link->query($sql);

if (!$query) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la consulta SQL: ' . mysql_error()
    ]);
    exit;
}

while ($row = mysqli_fetch_assoc($query)) {
    $row['diaTexto'] = convertirDiaSemana($row['dia']);
    $data[] = $row;
}

// Función para traducir número de día a texto
function convertirDiaSemana($dia) {
    $dias = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    return isset($dias[$dia]) ? $dias[$dia] : 'Desconocido';
}

echo json_encode([
    'status' => 'success',
    'message' => 'Horarios obtenidos correctamente',
    'data' => $data
]);
