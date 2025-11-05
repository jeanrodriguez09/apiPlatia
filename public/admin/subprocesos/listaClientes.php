<?php
// Conexión a la base de datos
include '../config.php';
// Verifica la conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

$data=[];

// Consulta a la base de datos
$sql = "SELECT 
            id, 
            numero, 
            CASE 
                WHEN nombre != '' OR nombre != NULL OR nombre != ' ' THEN nombre 
                ELSE 'Sin nombre'
            END AS nombre,
            email, 
            estado 
        FROM clientes";
$resultado = $link->query($sql);

if ($resultado->num_rows > 0) {
    $c = 1; // Contador para la primera columna
    while ($fila = $resultado->fetch_assoc()) { 
        // Formatear el estado como se necesita
        if($fila['estado']==1){
            $estado = '<span class="badge bg-success">Activo</span>' ;
        }elseif($fila['estado']==2){
            $estado = '<span class="badge bg-danger">No especifica</span>';
        }elseif($fila['estado']==0){
            $estado = '<span class="badge bg-warning">Bloqueado</span>';
        }else{
            $estado = '<span class="badge bg-danger">No identificado</span>';
        }

        // Agregar los datos al array
        $data[] = [
            'codigo'        => $fila['id'],
            'nombreCliente' => htmlspecialchars($fila['nombre']),
            'numero'        => htmlspecialchars($fila['numero']),
            'correo'        => htmlspecialchars($fila['correo']),
            'idEstado'      => $fila['estado'],
            'estado'        => $estado
        ];
    }
}

// Retornar los datos en formato JSON
echo json_encode(['data' => $data]);

// Cerrar conexión
$link->close();
?>