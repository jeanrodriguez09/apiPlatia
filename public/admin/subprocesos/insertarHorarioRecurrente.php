<?php
date_default_timezone_set('America/Asuncion');
include '../config.php';
header('Content-Type: application/json');

// Función para manejar errores centralizados
function throwError($message) {
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

try {
    if (
        !isset($_POST['idItem']) || !isset($_POST['dia']) ||
        !isset($_POST['horaInicio']) || !isset($_POST['horaFin']) ||
        !isset($_POST['cod_usuario'])
    ) {
        throwError('Faltan datos obligatorios');
    }

    $idItem = intval($_POST['idItem']);
    $diaSemana = intval($_POST['dia']);
    $horaInicio = $_POST['horaInicio'];
    $horaFin = $_POST['horaFin'];
    $codUsuario = intval($_POST['cod_usuario']);
    $fechaCreacion = date('Y-m-d H:i:s');

    // Validación de hora
    if ($horaInicio >= $horaFin) {
        throwError('La hora de inicio debe ser menor a la hora de fin');
    }

    // Validar solapamiento
    $sqlSolape = "SELECT COUNT(*) AS total
                  FROM itemHorarioRecurrente
                  WHERE idItem = $idItem
                  AND dia = $diaSemana
                  AND estado = 1
                  AND (
                        (horaInicio <= '$horaInicio' AND horaFin > '$horaInicio') OR
                        (horaInicio < '$horaFin' AND horaFin >= '$horaFin') OR
                        ('$horaInicio' <= horaInicio AND '$horaFin' >= horaFin)
                      )";

    $resSolape = $link->query($sqlSolape);
    if (!$resSolape) throwError('Error validando solapamiento: ' . $link->error);

    $rowSolape = mysqli_fetch_assoc($resSolape);
    if ($rowSolape['total'] > 0) {
        throwError('El horario ingresado se solapa con otro ya existente');
    }

    // Insertar
    $sqlInsert = "INSERT INTO itemHorarioRecurrente 
    (idItem, dia, horaInicio, horaFin, estado, fechaCreacion, idUsuarioCreador)
    VALUES ($idItem, $diaSemana, '$horaInicio', '$horaFin', 1, '$fechaCreacion', $codUsuario)";

    $resInsert = $link->query($sqlInsert);
    if (!$resInsert) throwError('No se pudo insertar el horario: ' . $link->error);

    echo json_encode(['status' => 'success', 'message' => 'Horario guardado correctamente']);
} catch (Exception $e) {
    throwError('Excepción no controlada: '.$e->getMessage());
}
?>
