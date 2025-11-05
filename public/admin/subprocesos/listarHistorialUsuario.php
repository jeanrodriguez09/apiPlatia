<?php
header('Content-Type: application/json');

$data=[];

require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/inc/helper.php'; // Asegúrate de llamar a las funciones de ayuda.

// Antes de usar $_POST['idb'], revisa si se recibe correctamente
if (!isset($_POST['idb']) || empty($_POST['idb'])) {
    echo json_encode(['data' => [], 'error' => 'ID de usuario no recibido.']);
    exit;
}

// Revisa el valor recibido
$idb = intval($_POST['idb']);

    $idb = $_POST['idb'];  // Recibe el ID del usuario

    // Consulta a la base de datos
    $sql = "SELECT  hu.nombre, 
                    hu.apellido, 
                    hu.estado, 
                    hu.idRol, 
                    r.descripcion AS nombreRol, 
                    hu.fechaCreacion, 
                    hu.idUsuarioCreador, 
                    uc.nombre AS nombreCreador,
                    uc.apellido AS apellidoCreador,
                    hu.fechaModificacion,
                    hu.idUsuarioModificacion,
                    um.nombre AS nombreModificador,
                    um.apellido AS apellidoModificador
            FROM historialusuarios hu 
            INNER JOIN roles r ON r.id=hu.idRol
            INNER JOIN usuarios uc ON uc.id=hu.idUsuarioCreador
            INNER JOIN usuarios um ON um.id=hu.idUsuarioModificacion
            WHERE hu.id=".intval($idb);

    $resultado = $link->query($sql);

    if ($resultado->num_rows > 0) {
        $c = 1; // Contador para la primera columna
        while ($fila = $resultado->fetch_assoc()) {
            // Formatear el estado como se necesita
            $nombreCompleto = $fila['nombre'].' '.$fila['apellido'];
            $nombreCompletoCreador = $fila['nombreCreador'].' '.$fila['apellidoCreador'];
            $nombreCompletoModificador = $fila['nombreModificador'].' '.$fila['apellidoModificador'];
            $estado = $fila['estado'] == 1 
                ? '<span class="badge bg-success">Activo</span>' 
                : '<span class="badge bg-danger">Desactivado</span>';
            
            // Agregar los datos al array
            $data[] = [
                'contador'                  => $c++,
                'nombreCompleto'            => htmlspecialchars($nombreCompleto),
                'estado'                    => $estado,
                'nombreRol'                 => $fila['nombreRol'],
                'fechaCreacion'             => $fila['fechaCreacion'],
                'nombreCompletoCreador'     => $nombreCompletoCreador,
                'fechaModificacion'         => $fila['fechaModificacion'],
                'nombreCompletoModificador' => $nombreCompletoModificador
            ];
        }
    }

    // Retornar los datos en formato JSON
    echo json_encode(['data' => $data]);

    // Cerrar conexión
    $link->close();
?>