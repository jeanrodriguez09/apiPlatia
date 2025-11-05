<?php
include '../config.php';
if(!isset($_POST['id'])){ echo json_encode(['status'=>'error','message'=>'ID no proporcionado']); exit; }
$id=intval($_POST['id']);
$sql="SELECT * FROM items WHERE id=$id";
$res=$link->query($sql);
if($res->num_rows>0){
    $row=$res->fetch_assoc();
    echo json_encode(['status'=>'success','data'=>$row]);
}else{
    echo json_encode(['status'=>'error','message'=>'Producto no encontrado']);
}
$link->close();
?>
