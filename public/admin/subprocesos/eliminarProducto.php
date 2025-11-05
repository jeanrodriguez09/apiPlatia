<?php
include '../config.php';
$id=intval($_POST['id']);
$sql="UPDATE items SET estado=0, fechaModificacion=NOW() WHERE id=$id";
if($link->query($sql)){
    echo json_encode(['status'=>'success','message'=>'Producto desactivado correctamente']);
}else{
    echo json_encode(['status'=>'error','message'=>'Error al desactivar producto: '.$link->error]);
}
$link->close();
?>
