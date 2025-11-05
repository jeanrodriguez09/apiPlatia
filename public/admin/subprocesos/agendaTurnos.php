<?php
include '../config.php';

// Consulta mejor estructurada y con alias descriptivos
$sql = "
    SELECT 
        a.id, 
        a.fechaHora, 
        a.estado, 
        a.observacion, 
        i.nombre AS servicio, 
        c.nombre AS cliente
    FROM agendamiento AS a
    INNER JOIN clientes AS c ON c.id = a.idCliente
    INNER JOIN items AS i ON i.id = a.idItem
";

$result = $link->query($sql);

if (!$result) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Error en la consulta: ' . $link->error]);
    exit;
}

$eventos = [];

while ($row = $result->fetch_assoc()) {
    // Verifica si fecha es válida
    if (empty($row['fechaHora']) || strtotime($row['fechaHora']) === false) {
        continue;
    }

    // $fecha = (new DateTime($row['fechaHora']))->format(DateTime::ATOM); // ISO 8601

    $evento = [
        'id'    => $row['id'],
        'title' => "Cita p/ {$row['cliente']}",
        'start' => $row['fechaHora'],
        'servicio' => "{$row['servicio']}",
        'observacion' => !empty($row['observacion']) ? $row['observacion'] : "Sin observaciones",
        'color' => $row['estado'] == 1 ? '#e67e22' : '#2ecc71'
    ];

    $eventos[] = $evento;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($eventos);
