<?php
include '../config.php';
$id = intval($_POST['id']);
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$duracion = $_POST['duracion'];
$estado = $_POST['estado'];

$sql="UPDATE items SET nombre='$nombre', descripcion='$descripcion', precio=$precio, duracion=$duracion, estado=$estado, fechaModificacion=NOW() WHERE id=$id";
if($link->query($sql)){
    echo json_encode(['status'=>'success','message'=>'Producto actualizado correctamente']);
}else{
    echo json_encode(['status'=>'error','message'=>'Error al actualizar producto: '.$link->error]);
}
$link->close();
?>
