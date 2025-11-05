<?php
date_default_timezone_set('America/Asuncion');
// Conexión a la base de datos
include '../config.php';
// Verifica la conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

$data=[];

if(!isset($_POST['idb'])){
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    exit;
}

$idb = $_POST['idb'];

// Consulta a la base de datos
$sql = "SELECT e.* FROM empresas e WHERE e.idUsuarioResponsable = $idb ORDER BY e.creada_en DESC";
$resultado = $link->query($sql);

if ($resultado->num_rows > 0) {
    $c = 1; // Contador para la primera columna
    while ($fila = $resultado->fetch_assoc()) {   
        
        if ($fila['activa'] == 1) {
            $estado = '<span class="badge bg-primary">Activo</span>';
        } else {
            $estado = '<span class="badge bg-danger">Inactivo</span>';
        }

        // Agregar los datos al array
        $data[] = [
            'contador'      => $c++,
            'codigo'        => $fila['id'],
            'nombre'        => htmlspecialchars($fila['nombre']),
            'descripcion'   => $fila['descripcionNegocio'],
            'estado'        => $estado,
            'idEstado'      => $fila['activa']
        ];
    }
}

// Retornar los datos en formato JSON
echo json_encode(['data' => $data]);

// Cerrar conexión
$link->close();
?>