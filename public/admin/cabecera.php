<?php
session_start();
if (!isset($_SESSION['username'])) {
	$cadena=NULL;
}else {
	$cadena="{$_GET['u']}";
}

//Si la cadena es Nula, entonces hay un error
if($cadena==NULL) {
	header("location:index.php?error=1");
  exit();
}

//Desmiembro toda la clave
$maximo = strlen($cadena);
$cadena_comienzo = "l6795";
$cadena_fin = "ha679";
$total = strpos($cadena,$cadena_comienzo);
$total2 = strpos($cadena,$cadena_fin);
$total3 = ($maximo - $total2 - 0);
$final = substr ($cadena,$total,-$total3);
$cod_usuario = substr("$final", 5);
include 'config.php';

//Tomo datos
$sql="SELECT 
        u.*,
        e.id AS idEmpresa
      FROM usuarios u 
      LEFT JOIN empresas e ON e.idUsuarioResponsable=u.id
      WHERE u.id='$cod_usuario'";
$result=$link->query($sql);
while ($row=mysqli_fetch_array($result)) {
    $usuario = ''.$row['usuario'].'';
	  $nombre_completo = ''.$row["nombre"].' '.$row["apellido"].'';
    $nombre = ''.$row["nombre"].'';
    $idEmpresa = ''.$row["idEmpresa"].'';
    $apellido = ''.$row["apellido"].'';
    $correo = ''.$row["correo"].'';
    $rango = ''.$row["idRol"].'';
    $cambioContrasena = ''.$row["cambioContrasena"].'';
}

//Tomo variables si lo hay
if(isset($_GET['c'])){ $comando="{$_GET['c']}"; } else {$comando=NULL;}
if(isset($_GET['id'])){ $idb="{$_GET['id']}"; } else {$idb=NULL;}
?>
<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" />