<?php
date_default_timezone_set('America/Asuncion');
// Conexión a la base de datos
include '../config.php';
// Verifica la conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

$data=[];

// Consulta a la base de datos
$sql = "SELECT p.*, c.nombre AS nombreCliente, c.numero AS contactoCliente FROM pedidos p INNER JOIN clientes c ON c.id=p.idCliente WHERE p.estado NOT IN (0, 1) ORDER BY p.fechaCreacion DESC";
$resultado = $link->query($sql);

if ($resultado->num_rows > 0) {
    $c = 1; // Contador para la primera columna
    while ($fila = $resultado->fetch_assoc()) {   
        
        if ($fila['estado'] == 0) {
            $estado = '<span class="badge bg-warning">Pendiente</span>';
        } else {
            $estado = '<span class="badge bg-info">Estado desconocido</span>';
        }

        // Agregar los datos al array
        $data[] = [
            'contador'  => $c++,
            'codigo'    => $fila['id'],
            'total'     => $fila['total'],
            'nombreCliente' => htmlspecialchars($fila['nombreCliente']),
            'estado' => $estado,
            'idEstado' => $fila['estado']
        ];
    }
}

// Retornar los datos en formato JSON
echo json_encode(['data' => $data]);

// Cerrar conexión
$link->close();
?>