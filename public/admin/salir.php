<?php
	session_start();
    date_default_timezone_set('America/Asuncion');
	if (!isset($_SESSION['username'])) {
	    $cadena=NULL;
	}
	else
	{
		$cadena="{$_GET['u']}";
	}

// Me conecto

include 'config.php'; 
$tbl_name="usuarios";

// Tomar la IP del usuario

if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
$realip = getenv( 'HTTP_X_FORWARDED_FOR' );
} elseif ( getenv( 'HTTP_CLIENT_IP' ) ) {
$realip = getenv( 'HTTP_CLIENT_IP' );
} else {
$realip = getenv( 'REMOTE_ADDR' );
}
$navegador = $_SERVER['HTTP_USER_AGENT']; 

    //Obtengo el verdadero valor de Codigo de Cliente
    $error="{$_GET['error']}";
    
    if($cadena==NULL)
    {
	header("location:index.php?error=1");
    }
    
    if($error==1)
    {
	header("location:index.php?error=1");
    }   
    $maximo = strlen($cadena);
    $cadena_comienzo = "l6795";
    $cadena_fin = "ha679";
    $total = strpos($cadena,$cadena_comienzo);
    $total2 = strpos($cadena,$cadena_fin);
    $total3 = ($maximo - $total2 - 0);
    $final = substr ($cadena,$total,-$total3);
    $cod_cliente = substr("$final", 5);    
// sumo las visitas y tomos datos del usuario
$sql2="SELECT * FROM usuarios WHERE id='$cod_cliente'";
$result2=$link->query($sql2);
while ($row2=mysqli_fetch_array($result2))
{
$usuarioCierre = ''.$row2["usuario"].'';
}


// Grabamos salida

$fecha_cierre_sesion = date('Y-m-d H:i:s');
$tipo = 'CIERRE DE SESIÓN';
$inserta_audita = "INSERT INTO sesiones_sistema (ip, navegador, fechaCreacion, usuario, tipo) VALUES ('$realip', '$navegador', '$fecha_cierre_sesion', '$usuarioCierre', '$tipo')";
$link->query($inserta_audita);

session_destroy();
header("location: index.php");
?>