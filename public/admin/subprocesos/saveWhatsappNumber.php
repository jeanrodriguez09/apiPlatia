<?php
session_start();
require_once '../config.php';

// --- helpers de encriptación (AES-256-CBC) ---
function encrypt_token($plaintext) {
    $hexKey = FB_TOKEN_ENCRYPTION_KEY;
    $key = hex2bin($hexKey);
    $ivlen = openssl_cipher_iv_length('aes-256-cbc');
    $iv = random_bytes($ivlen);
    $cipher = openssl_encrypt($plaintext, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    // Guardamos IV + ciphertext (base64) para poder desencriptar luego
    return base64_encode($iv . $cipher);
}

function decrypt_token($b64) {
    $hexKey = FB_TOKEN_ENCRYPTION_KEY;
    $key = hex2bin($hexKey);
    $data = base64_decode($b64);
    $ivlen = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $ivlen);
    $cipher = substr($data, $ivlen);
    return openssl_decrypt($cipher, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
}

// Validar sesión y token
if (!isset($_SESSION['fb_link_empresa']) || !isset($_SESSION['fb_long_token'])) {
    // Si el llamado fue directo sin sesión, retornamos error
    echo "<script>alert('Sesión de vinculación no encontrada. Volvé a intentar el proceso.'); window.close();</script>";
    exit;
}

$idEmpresa = intval($_SESSION['fb_link_empresa']);
$longToken = $_SESSION['fb_long_token'];

// Obtenemos datos del POST (desde callback automático o el form)
$phone_number_id = isset($_POST['phone_number_id']) ? trim($_POST['phone_number_id']) : null;
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;

if (empty($phone_number_id) || empty($telefono) || $idEmpresa <= 0) {
    echo "<script>alert('Faltan datos para vincular el número.'); window.close();</script>";
    exit;
}

// Encriptar token antes de guardar
$encToken = encrypt_token($longToken);

// Guardar en DB: si existe idEmpresa -> update, si no -> insert
// Asumimos que sólo una fila por idEmpresa. Ajusta si necesitás múltiples números.
$stmt_check = $link->prepare("SELECT id FROM numeros_whatsapp WHERE idEmpresa = ?");
$stmt_check->bind_param("i", $idEmpresa);
$stmt_check->execute();
$res_check = $stmt_check->get_result();

if ($res_check && $res_check->num_rows > 0) {
    $row = $res_check->fetch_assoc();
    $idRow = $row['id'];
    $stmt = $link->prepare("UPDATE numeros_whatsapp SET telefono = ?, phone_number_id = ?, access_token = ?, activo = 1, creado_en = NOW() WHERE id = ?");
    $stmt->bind_param("sssi", $telefono, $phone_number_id, $encToken, $idRow);
    $ok = $stmt->execute();
} else {
    $stmt = $link->prepare("INSERT INTO numeros_whatsapp (idEmpresa, telefono, phone_number_id, access_token, activo, creado_en) VALUES (?, ?, ?, ?, 1, NOW())");
    $stmt->bind_param("isss", $idEmpresa, $telefono, $phone_number_id, $encToken);
    $ok = $stmt->execute();
}

if ($ok) {
    // Limpiamos variables de sesión de OAuth por seguridad
    unset($_SESSION['fb_long_token']);
    unset($_SESSION['fb_oauth_state']);
    unset($_SESSION['fb_waba_id']);
    unset($_SESSION['fb_link_empresa']);
    // Cerramos popup y refrescamos la ventana opener
    echo "<script>
            if (window.opener) {
                try { window.opener.location.reload(); } catch(e) {}
            }
            alert('Número vinculado correctamente.');
            window.close();
          </script>";
    exit;
} else {
    echo "<script>alert('Error guardando en la base de datos.'); window.close();</script>";
    exit;
}
