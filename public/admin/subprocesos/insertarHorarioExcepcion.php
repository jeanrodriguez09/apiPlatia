<?php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // Debe definir $link como conexión mysqli
header('Content-Type: application/json');

function throwError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

try {
    // Campos obligatorios
    if (!isset($_POST['idItem']) || !isset($_POST['fecha']) || !isset($_POST['cod_usuario'])) {
        throwError('Faltan datos obligatorios (idItem, fecha, cod_usuario)');
    }

    $idItem     = intval($_POST['idItem']);
    $fecha      = trim($_POST['fecha']); // esperado YYYY-MM-DD
    $motivo     = isset($_POST['motivo']) ? trim($_POST['motivo']) : null;
    $codUsuario = intval($_POST['cod_usuario']);
    $fechaCreacion = date('Y-m-d H:i:s');

    // Validar formato de fecha (YYYY-MM-DD)
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    if (!$d || $d->format('Y-m-d') !== $fecha) {
        throwError('Formato de fecha inválido. Use YYYY-MM-DD');
    }

    // Validar que no exista ya una excepción para ese idItem y fecha
    $stmt = $link->prepare("SELECT COUNT(*) AS total FROM itemHorarioExcepciones WHERE idItem = ? AND fecha = ? AND estado = 1");
    if (!$stmt) throwError('Error preparando consulta: ' . $link->error);

    $stmt->bind_param('is', $idItem, $fecha);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res) { $stmt->close(); throwError('Error ejecutando consulta: ' . $link->error); }

    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row && intval($row['total']) > 0) {
        throwError('Ya existe una excepción registrada para esa fecha');
    }

    // Insertar excepción (nota: motivo puede ser NULL)
    $stmtIns = $link->prepare("INSERT INTO itemHorarioExcepciones (idItem, fecha, motivo, estado, fechaCreacion, idUsuarioCreador) VALUES (?, ?, ?, 1, ?, ?)");
    if (!$stmtIns) throwError('Error preparando insert: ' . $link->error);

    // Para permitir NULL en motivo debemos pasarlo correctamente
    if ($motivo === null || $motivo === '') {
        $motivo_param = null;
        // bind_param requiere types; usamos 's' for string but will set null if empty
        $stmtIns->bind_param('isssi', $idItem, $fecha, $motivo_param, $fechaCreacion, $codUsuario);
    } else {
        // escape no necesario porque usamos prepared statement, pero limpiamos espacios
        $stmtIns->bind_param('isssi', $idItem, $fecha, $motivo, $fechaCreacion, $codUsuario);
    }

    $exec = $stmtIns->execute();
    if (!$exec) {
        $err = $stmtIns->error ?: $link->error;
        $stmtIns->close();
        throwError('Error al insertar excepción: ' . $err);
    }

    $stmtIns->close();

    echo json_encode(['status' => 'success', 'message' => 'Excepción guardada correctamente']);

} catch (Exception $e) {
    throwError('Excepción no controlada: ' . $e->getMessage());
}
