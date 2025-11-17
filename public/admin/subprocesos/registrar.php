<?php
session_start();
require '../config.php'; // tu archivo de conexión

// =======================
// VALIDAR POST
// =======================
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: registro.php");
    exit;
}

// =======================
// DATOS DEL USUARIO
// =======================
$nombre      = $_POST['nombre'];
$apellido    = $_POST['apellido'];
$correoUser  = $_POST['correo'];

// =======================
// DATOS DE EMPRESA
// =======================
$empresa_nombre    = $_POST['empresa_nombre'];
$empresa_email     = $_POST['empresa_email'];
$empresa_direccion = $_POST['empresa_direccion'];
$empresa_ruc       = $_POST['empresa_ruc'];
$latitud           = $_POST['empresa_latitud'];
$longitud          = $_POST['empresa_longitud'];

// =======================
// GENERAR CONTRASEÑA AUTOMATICA
// =======================
$passPlano = "Usr" . date("Y") . "@" . rand(1000, 9999);
$passHash = password_hash($passPlano, PASSWORD_DEFAULT);

// =======================
// INSERTAR EMPRESA
// =======================
$sqlEmpresa = "INSERT INTO empresas 
(nombre, email_contacto, activa, creada_en, direccion, latitud, longitud, ruc, idUsuarioResponsable) 
VALUES (?, ?, 1, NOW(), ?, ?, ?, ?, 0)";

$stmt = $conexion->prepare($sqlEmpresa);
$stmt->bind_param("ssssdds", 
    $empresa_nombre,
    $empresa_email,
    $empresa_direccion,
    $latitud,
    $longitud,
    $empresa_ruc
);

if (!$stmt->execute()) {
    die("Error al insertar empresa: " . $stmt->error);
}

$idEmpresa = $stmt->insert_id;
$stmt->close();

// =======================
// INSERTAR USUARIO (ADMIN)
// =======================
$sqlUsuario = "INSERT INTO usuarios 
(usuario, contrasena, nombre, apellido, estado, idRol, fechaCreacion, 
 idUsuarioCreador, correo, cambioContrasena, legajo, idEmpresa)
VALUES (?, ?, ?, ?, 1, 1, NOW(), 0, ?, 1, 0, ?)";

$usuarioLogin = $correoUser; // usuario = correo

$stmt2 = $conexion->prepare($sqlUsuario);
$stmt2->bind_param("ssssssi", 
    $usuarioLogin,
    $passHash,
    $nombre,
    $apellido,
    $correoUser,
    $idEmpresa
);

if (!$stmt2->execute()) {
    die("Error al insertar usuario: " . $stmt2->error);
}

$idUsuario = $stmt2->insert_id;
$stmt2->close();

// =======================
// ACTUALIZAR EMPRESA: asignar responsable
// =======================
$conexion->query("UPDATE empresas SET idUsuarioResponsable = $idUsuario WHERE id = $idEmpresa");

// =======================
// LOGIN AUTOMÁTICO
// =======================
$_SESSION['idUsuario'] = $idUsuario;
$_SESSION['usuario']   = $usuarioLogin;
$_SESSION['idEmpresa'] = $idEmpresa;
$_SESSION['nombre']    = $nombre . " " . $apellido;

// =======================
// REDIRECCIONAR
// =======================
header("Location: dashboard.php?iniciado=1");
exit;

?>
