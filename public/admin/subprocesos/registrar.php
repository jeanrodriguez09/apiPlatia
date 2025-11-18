<?php
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
// registrar.php - responde siempre JSON
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config.php'; // debe definir $link (mysqli)

// Método POST requerido
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

// Recoger inputs (evitar NULL)
$nombre      = trim($_POST['nombre'] ?? '');
$apellido    = trim($_POST['apellido'] ?? '');
$correoUser  = trim($_POST['correo'] ?? '');

$empresa_nombre    = trim($_POST['empresa_nombre'] ?? '');
$empresa_email     = trim($_POST['empresa_email'] ?? '');
$empresa_direccion = trim($_POST['empresa_direccion'] ?? '');
$empresa_ruc       = trim($_POST['empresa_ruc'] ?? '');
$empresa_numero    = trim($_POST['numero_parte'] ?? '');
// Limpiar caracteres no numéricos
$empresa_numero    = preg_replace('/\D/', '', $empresa_numero);
// Validar 6 dígitos
if (!preg_match('/^\d{9}$/', $empresa_numero)) {
    echo json_encode(["status" => "error", "message" => "Número inválido. Deben ser exactamente 9 dígitos después de 595"]);
    exit;
}
// Formato final
$telefono_completo = "595" . $empresa_numero;
$latitud           = trim($_POST['empresa_latitud'] ?? '');
$longitud          = trim($_POST['empresa_longitud'] ?? '');

// Validaciones básicas
if (!$nombre || !$apellido || !$correoUser) {
    echo json_encode(["status" => "error", "message" => "Completa los datos del usuario."]);
    exit;
}
if (!filter_var($correoUser, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Ingresá un correo electrónico válido."]);
    exit;
}
if (!$empresa_nombre || $latitud === '' || $longitud === '') {
    echo json_encode(["status" => "error", "message" => "Faltan datos de la empresa."]);
    exit;
}

// Generar contraseña temporal (plana) y hash
$passPlano = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
$passHash = password_hash($passPlano, PASSWORD_DEFAULT);

// ===== INSERT EMPRESA =====
// Nota: query tiene 6 placeholders: nombre, email_contacto, direccion, latitud, longitud, ruc
$sqlEmpresa = "INSERT INTO empresas 
  (nombre, email_contacto, idRubro, activa, creada_en, direccion, latitud, longitud, ruc, idUsuarioResponsable) 
  VALUES (?, ?, 3, 1, NOW(), ?, ?, ?, ?, 0)";

$stmt = $link->prepare($sqlEmpresa);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error al preparar empresa: " . $link->error]);
    exit;
}

// Tipos: s (nombre), s (email), s (direccion), d (latitud), d (longitud), s (ruc)
$stmt->bind_param(
    "sssdds",
    $empresa_nombre,
    $empresa_email,
    $empresa_direccion,
    $latitud,
    $longitud,
    $empresa_ruc
);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al insertar empresa: " . $stmt->error]);
    $stmt->close();
    exit;
}

$idEmpresa = $stmt->insert_id;
$stmt->close();

// ===== INSERT NUMERO EMPRESA =====
// Campos: usuario, contrasena, nombre, apellido, estado, idRol, fechaCreacion, idUsuarioCreador, correo, cambioContrasena, legajo, idEmpresa
$sqlNumeroEmpresa = "INSERT INTO numeros_whatsapp 
  (idEmpresa, telefono, activo, creado_en)
  VALUES (?, ?, 1, NOW())";

$stmt3 = $link->prepare($sqlNumeroEmpresa);
if (!$stmt3) {
    echo json_encode(["status" => "error", "message" => "Error al preparar numeros_whatsapp: " . $link->error]);
    exit;
}

$stmt3->bind_param(
    "is",
    $idEmpresa,
    $empresa_numero
);

if (!$stmt3->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al insertar numeros_whatsapp: " . $stmt3->error]);
    $stmt3->close();
    exit;
}

$stmt3->close();

// ===== INSERT USUARIO =====
// Campos: usuario, contrasena, nombre, apellido, estado, idRol, fechaCreacion, idUsuarioCreador, correo, cambioContrasena, legajo, idEmpresa
$sqlUsuario = "INSERT INTO usuarios 
  (usuario, contrasena, nombre, apellido, estado, idRol, fechaCreacion, idUsuarioCreador, correo, cambioContrasena, legajo, idEmpresa)
  VALUES (?, ?, ?, ?, 1, 1, NOW(), 0, ?, 1, 0, ?)";

$usuarioLogin = $correoUser;

$stmt2 = $link->prepare($sqlUsuario);
if (!$stmt2) {
    echo json_encode(["status" => "error", "message" => "Error al preparar usuario: " . $link->error]);
    exit;
}

// Tipos: s(usuario), s(contrasena), s(nombre), s(apellido), s(correo), i(idEmpresa)
$stmt2->bind_param(
    "sssssi",
    $usuarioLogin,
    $passHash,
    $nombre,
    $apellido,
    $correoUser,
    $idEmpresa
);

if (!$stmt2->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al insertar usuario: " . $stmt2->error]);
    $stmt2->close();
    exit;
}

$idUsuario = $stmt2->insert_id;
$stmt2->close();

// ===== ACTUALIZAR EMPRESA para asignar responsable =====
$updateSql = $link->prepare("UPDATE empresas SET idUsuarioResponsable = ? WHERE id = ?");
if ($updateSql) {
    $updateSql->bind_param("ii", $idUsuario, $idEmpresa);
    $updateSql->execute();
    $updateSql->close();
}

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.plat.com.py';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'info@platia.plat.com.py';
        $mail->Password   = 'ht0940VG!';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('info@platia.plat.com.py', 'PlatIA');
        $mail->addAddress($correoUser, $nombre);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->Subject = "Creación de usuario [ $empresa_nombre ]- PLATIA";

        $mail->Body = '
            <div style="font-family: Arial, sans-serif; background-color:#f7f7f7; padding:25px;">
                <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.08);">
            
                    <!-- Encabezado -->
                    <div style="background:#4a6cf7; padding:20px; color:white; text-align:center;">
                        <h2 style="margin:0; font-weight:600;">Registro completado</h2>
                        <p style="margin:5px 0 0; font-size:14px;">PLATIA</p>
                    </div>
            
                    <!-- Contenido principal -->
                    <div style="padding:25px; color:#333;">
            
                        <p style="font-size:16px; margin-top:0;">
                            Estimado usuario,
                        </p>
            
                        <p style="line-height:1.6; font-size:15px;">
                            Su acceso al sistema <strong>PLATIA</strong> ha sido creado correctamente.  
                            A continuación encontrará sus credenciales de acceso.  
                            Por favor guarde esta información en un lugar seguro.
                        </p>
            
                        <!-- Caja de credenciales -->
                        <div style="background:#f0f3ff; padding:15px 20px; border-radius:8px; margin:20px 0; border:1px solid #dce3ff;">
                            <p style="margin:0; font-size:15px;">
                                <strong>Usuario:</strong> <span style="color:#2e4de6;">'.$usuarioLogin.'</span>
                            </p>
                            <p style="margin:8px 0 0; font-size:15px;">
                                <strong>Contraseña temporal:</strong> <span style="color:#2e4de6;">'.$passPlano.'</span>
                            </p>
                        </div>
            
                        <p style="line-height:1.6; font-size:15px;">
                            Al ingresar por primera vez, se recomienda actualizar su contraseña para mayor seguridad.
                        </p>
            
                        <p style="line-height:1.6; font-size:15px;">
                            Si usted no solicitó este acceso, por favor ignore este mensaje o contacte al equipo de soporte.
                        </p>
            
                        <p style="margin-top:25px; font-size:14px; color:#666;">
                            Atentamente,<br>
                            <strong>Equipo PLATIA</strong>
                        </p>
            
                    </div>
            
                    <!-- Footer -->
                    <div style="background:#f0f0f0; padding:15px; text-align:center; font-size:12px; color:#777;">
                        Este correo fue enviado automáticamente. Por favor, no responda a este mensaje.
                    </div>
            
                </div>
            </div>

        ';


        $mail->send();

    } catch (Exception $e) {
        
    }

// ===== RESPUESTA JSON ÉXITO =====
echo json_encode([
    "status" => "success",
    "message" => "Registro exitoso, se ha realizado el envio del acceso a su cuenta de correo personal.",
    "data" => [
        "idUsuario" => (int)$idUsuario,
        "idEmpresa" => (int)$idEmpresa
        // "usuario"   => $usuarioLogin,
        // "password_temporal" => $passPlano
    ]
]);

exit;
