<?php
include '../config.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$duracion = $_POST['duracion'];
$estado = $_POST['estado'];
$idEmpresa = $_POST['idEmpresa'];
$idTipo = 2; // Servicios

// Preparar la consulta
$stmt = $link->prepare("INSERT INTO items (nombre, descripcion, precio, duracion, estado, idEmpresa, idTipo, fechaCreacion) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
if($stmt === false){
    echo json_encode(['status'=>'error','message'=>'Error al preparar la consulta: '.$link->error]);
    exit;
}

// Asignar parámetros: s=string, i=int, d=double
$stmt->bind_param("ssdiiii", $nombre, $descripcion, $precio, $duracion, $estado, $idEmpresa, $idTipo);

// Ejecutar
if($stmt->execute()){
    echo json_encode(['status'=>'success','message'=>'Producto agregado correctamente']);
}else{
    echo json_encode(['status'=>'error','message'=>'Error al agregar producto: '.$stmt->error]);
}

$stmt->close();
$link->close();
?>
