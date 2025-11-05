<?php
date_default_timezone_set('America/Asuncion');
include '../config.php';
header('Content-Type: application/json');

function throwError($message){
    echo json_encode(['status' => 'error', 'message' => $message]);
    exit;
}

try {
    if(!isset($_POST['idHorario']) || !isset($_POST['cod_usuario'])){
        throwError('Faltan datos obligatorios');
    }

    $idHorario = intval($_POST['idHorario']);
    $codUsuario = intval($_POST['cod_usuario']);
    $fechaModificacion = date('Y-m-d H:i:s');

    // Validar que exista
    $sqlCheck = "SELECT id FROM itemsHorariosExcepcionales WHERE id = $idHorario AND estado = 1";
    $resCheck = $link->query($sqlCheck);
    if(!$resCheck){
        throwError('Error validando horario: ' . $link->error);
    }
    if($resCheck->num_rows === 0){
        throwError('El horario no existe o ya fue inactivado anteriormente');
    }

    // Eliminación lógica
    $sqlDelete = "UPDATE itemsHorariosExcepcionales
                  SET estado = 0,
                      idUsuarioModificacion = $codUsuario,
                      fechaModificacion = '$fechaModificacion'
                  WHERE id = $idHorario";
    $resDelete = $link->query($sqlDelete);
    if(!$resDelete){
        throwError('No se pudo eliminar: ' . $link->error);
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Horario especial eliminado correctamente'
    ]);

} catch (Exception $e) {
    throwError('Excepción no controlada: ' . $e->getMessage());
}
