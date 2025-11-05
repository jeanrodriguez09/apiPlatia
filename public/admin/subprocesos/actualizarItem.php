<?php
header('Content-Type: application/json');
date_default_timezone_set('America/Asuncion');
require_once '../config.php';

$idItem = intval($_POST['idItem']);
$nombre = $link->real_escape_string($_POST['nombre']);
$duracion = intval($_POST['duracion']);
$precio = floatval($_POST['precio']);
$descripcion = $link->real_escape_string($_POST['descripcion']);

// ✅ Actualizar datos del item
$query = "UPDATE items 
          SET nombre='$nombre', duracion='$duracion', precio='$precio', descripcion='$descripcion', fechaModificacion=NOW() 
          WHERE id = $idItem";
if (!$link->query($query)) {
    echo json_encode(['status'=>'error','message'=>'Error al actualizar el item: '.$link->error]);
    exit;
}

// ✅ Actualizar palabras clave (opcional)
if (isset($_POST['palabrasClave'])) {
    $palabrasClave = $_POST['palabrasClave']; // array de select2

    // Borro las keywords previas
    $link->query("DELETE FROM palabraClaveItems WHERE idItem = $idItem");

    // Inserto las nuevas (si existen)
    foreach ($palabrasClave as $palabra) {
        $palabra = $link->real_escape_string($palabra);
        $link->query("INSERT INTO palabraClaveItems (idItem, palabraClave, fechaCreacion, idUsuarioCreador) 
                      VALUES ($idItem, '$palabra', NOW(), 1)");
    }
}

echo json_encode(['status'=>'success','message'=>'Item actualizado correctamente']);
