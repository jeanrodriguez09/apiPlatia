<?php
date_default_timezone_set('America/Asuncion');
// Conexión a la base de datos
include '../config.php';
// Verifica la conexión
if ($link->connect_error) {
    die("Error de conexión: " . $link->connect_error);
}

$data=[];

if(!isset($_POST['idb']) || !isset($_POST['idEmpresa'])){
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
    exit;
}

$idb = $_POST['idb'];
$idEmpresa = $_POST['idEmpresa'];

// Consulta a la base de datos
$sql = "SELECT  nw.id,
                nw.telefono,
                nw.activo 
        FROM numeros_whatsapp nw 
        INNER JOIN empresas e ON e.id=nw.idEmpresa AND e.idUsuarioResponsable=$idb
        WHERE nw.idEmpresa = $idEmpresa 
        ORDER BY nw.creado_en DESC";
$resultado = $link->query($sql);

if ($resultado->num_rows > 0) {
    $c = 1; // Contador para la primera columna
    while ($fila = $resultado->fetch_assoc()) {   
        
        if ($fila['activo'] == 1) {
            $estado = '<span class="badge bg-primary">Activo</span>';
        } else {
            $estado = '<span class="badge bg-danger">Inactivo</span>';
        }

        // Agregar los datos al array
        $data[] = [
            'contador'  => $c++,
            'codigo'    => $fila['id'],
            'numero' => htmlspecialchars($fila['telefono']),
            'estado'    => $estado,
            'idEstado'    => $fila['activo']
        ];
    }
}

// Retornar los datos en formato JSON
echo json_encode(['data' => $data]);

// Cerrar conexión
$link->close();
?>