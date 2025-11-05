<?php
date_default_timezone_set('America/Asuncion');
include '../config.php';
header('Content-Type: application/json');

// Validación básica de parámetros
$requeridos = ['idServicio', 'idCliente', 'idAgenda', 'cod_usuario'];
foreach ($requeridos as $campo) {
    if (empty($_POST[$campo])) {
        echo json_encode([
            'status' => 'warning',
            'message' => "Falta el campo obligatorio: $campo"
        ]);
        exit;
    }
}

$idServicio   = intval($_POST['idServicio']);
$idCliente    = intval($_POST['idCliente']);
$idAgenda     = intval($_POST['idAgenda']);
$observacion  = isset($_POST['observacion']) ? trim($_POST['observacion']) : '';
$cod_usuario  = intval($_POST['cod_usuario']);
$fechaActual  = date('Y-m-d H:i:s');

// 1️⃣ Obtener fecha y hora real del horario seleccionado
$sqlHorario = "SELECT fecha, horaInicio FROM agendaDisponible WHERE id = $idAgenda AND estado = 1 LIMIT 1";
$resHorario = $link->query($sqlHorario);

if (!$resHorario || mysqli_num_rows($resHorario) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'El horario seleccionado no está disponible']);
    exit;
}

$horario = mysqli_fetch_assoc($resHorario);
$fechaHora = $horario['fecha'] . ' ' . $horario['horaInicio']; // formato Y-m-d H:i:s

// Iniciar transacción
mysqli_begin_transaction($link);

try {
    // 2️⃣ Insertar la reserva
    $sqlInsert = "INSERT INTO agendamiento 
        (idItem, idAgendaDisponible, idCliente, fechaHora, estado, fechaCreacion, idUsuarioModifcacion, observacion)
        VALUES 
        ($idServicio, $idAgenda, $idCliente, '$fechaHora', 1, '$fechaActual', $cod_usuario, '$observacion')";
    
    if (!$link->query($sqlInsert)) {
        throw new Exception('Error al guardar la reserva: ' . mysqli_error($link));
    }

    // 3️⃣ Deshabilitar el horario
    $sqlUpdate = "UPDATE agendaDisponible SET estado = 0 WHERE id = $idAgenda";
    if (!$link->query($sqlUpdate)) {
        throw new Exception('Error al actualizar el horario: ' . mysqli_error($link));
    }

    // Confirmar
    mysqli_commit($link);

    echo json_encode([
        'status' => 'success',
        'message' => 'Reserva guardada correctamente'
    ]);

} catch (Exception $e) {
    mysqli_rollback($link);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

mysqli_close($link);
?>
