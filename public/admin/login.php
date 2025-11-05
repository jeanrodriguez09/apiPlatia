<?php
session_start();
ob_start();
include 'config.php'; 
date_default_timezone_set('America/Asuncion');
$tbl_name="usuarios"; 
$myusername=$_POST['username'];
$mypassword=$_POST['password'];

$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);

$sql="SELECT * FROM $tbl_name WHERE usuario='$myusername'";
$result=$link->query($sql);

$count=mysqli_num_rows($result);

// Tomar la IP del usuario y su Navegador

if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
$realip = getenv( 'HTTP_CLIENT_IP' );
} else {
$realip = getenv( 'REMOTE_ADDR' );
}

$navegador = $_SERVER['HTTP_USER_AGENT']; 

// logueo correctamente

if($count==1){
    
// sumo las visitas y tomos datos del usuario
$sql2="SELECT * FROM $tbl_name WHERE usuario='$myusername'";
$result2=$link->query($sql2);
while ($row2=mysqli_fetch_array($result2))
{
	$cod_cliente = ''.$row2["id"].'';
	$rol = ''.$row2["rango"].'';
	$estado=''.$row2["estado"].'';
	$pase = ''.$row2["contrasena"].'';
}

    if (password_verify($mypassword, $pase)) {
        
        // Guardemos Datos de Acceso Correcto
        
        $fecha_inicio_sesion = date('Y-m-d H:i:s');
        $tipo = 'ACCESO CONCEDIDO';
        $inserta_audita = "INSERT INTO sesiones_sistema (ip, navegador, fechaCreacion, usuario, tipo) VALUES ('$realip', '$navegador', '$fecha_inicio_sesion', '$myusername', '$tipo')";
        $link->query($inserta_audita);
        
        
        // Guardo Session y Cifro
        $cod_cliente_1 = substr(md5(uniqid(rand())),0,6);
        $cod_cliente_2 = substr(md5(uniqid(rand())),0,8);
        $falso = substr(md5(uniqid(rand())),0,10);
        $cod_cliente_ext = 'da' .$cod_cliente_1 . 'l6795' .$cod_cliente . 'ha679' .$cod_cliente_2;
        $_SESSION['username'] = $myusername;
        $_SESSION['password'] = $mypassword;
        
        if($estado==1){
            header("location:menu.php?u=$cod_cliente_ext&c=$falso");
        }else{
            header("location:index.php?m=3");
        }
        
    }else{
        
        // Guardemos Datos de Acceso Incorrecto. PASE INCORRECTO

        $fecha_inicio_sesion = date('Y-m-d H:i:s');
        $tipo = 'FALLO EN CREDENCIALES';
        $inserta_audita = "INSERT INTO sesiones_sistema (ip, navegador, fechaCreacion, usuario, tipo) VALUES ('$realip', '$navegador', '$fecha_inicio_sesion', '$myusername', '$tipo')";
        $link->query($inserta_audita);
        header("location:index.php?m=2");
        
    }


}
else {

// Guardemos Datos de Acceso Incorrecto. NO SE ENCONTRARON DATOS DEL USUARIO INGRESADO.

$fecha_inicio_sesion = date('Y-m-d H:i:s');
$tipo = 'USUARIO NO EXISTE';
$inserta_audita = "INSERT INTO sesiones_sistema (ip, navegador, fechaCreacion, usuario, tipo) VALUES ('$realip', '$navegador', '$fecha_inicio_sesion', '$myusername', '$tipo')";
$link->query($inserta_audita);
header("location:index.php?m=1");
}

ob_end_flush();
?>