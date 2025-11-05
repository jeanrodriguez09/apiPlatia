<?php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // Debe definir $link como conexión mysqli
header('Content-Type: application/json');

function throwError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

try {
    // Validar datos obligatorios
    if (
        !isset($_POST['idItem']) ||
        !isset($_POST['fecha']) ||
        !isset($_POST['horaInicio']) ||
        !isset($_POST['horaFin']) ||
        !isset($_POST['cod_usuario'])
    ) {
        throwError('Faltan datos obligatorios');
    }

    $idItem        = intval($_POST['idItem']);
    $fecha         = trim($_POST['fecha']);
    $horaInicio    = trim($_POST['horaInicio']);
    $horaFin       = trim($_POST['horaFin']);
    $codUsuario    = intval($_POST['cod_usuario']);
    $fechaCreacion = date('Y-m-d H:i:s');

    // Validación básica
    if ($horaInicio >= $horaFin) {
        throwError('La hora de inicio debe ser menor a la hora fin');
    }

    // Validar que no se solape otro horario especial
    $sqlSolape = "SELECT COUNT(*) AS total
        FROM itemsHorariosExcepcionales
        WHERE idItem = ?
        AND fecha = ?
        AND (
            (horaInicio <= ? AND horaFin > ?) OR
            (horaInicio < ? AND horaFin >= ?) OR
            (? <= horaInicio AND ? >= horaFin)
        )";

    $stmt = $link->prepare($sqlSolape);
    if (!$stmt) throwError('Error preparando validación: ' . $link->error);

    $stmt->bind_param('isssssss',
        $idItem,
        $fecha,
        $horaInicio, $horaInicio,
        $horaFin, $horaFin,
        $horaInicio, $horaFin
    );
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row['total'] > 0) {
        throwError('El horario especial se solapa con otro ya existente');
    }

    // Insertar horario especial
    $sqlInsert = "INSERT INTO itemsHorariosExcepcionales
        (idItem, fecha, horaInicio, horaFin, idUsuarioCreador, fechaCreacion)
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt2 = $link->prepare($sqlInsert);
    if (!$stmt2) throwError('Error preparando INSERT: ' . $link->error);

    $stmt2->bind_param('isssis',
        $idItem, $fecha, $horaInicio, $horaFin, $codUsuario, $fechaCreacion
    );
    $ok = $stmt2->execute();
    $stmt2->close();

    if (!$ok) throwError('Error ejecutando INSERT: ' . $link->error);

    echo json_encode([
        'status' => 'success',
        'message' => 'Horario especial registrado correctamente'
    ]);

} catch (Exception $e) {
    throwError('Excepción no controlada: ' . $e->getMessage());
}
