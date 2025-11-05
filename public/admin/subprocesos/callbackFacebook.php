<?php
session_start();
require_once '../config.php';

// Validar 'state' para prevenir CSRF
if (!isset($_GET['state']) || !isset($_SESSION['fb_oauth_state']) || !hash_equals($_SESSION['fb_oauth_state'], $_GET['state'])) {
    echo "Error de verificación (estado inválido).";
    exit;
}

// Validar 'code'
if (!isset($_GET['code'])) {
    echo "No se recibió código de autorización.";
    exit;
}

$code = $_GET['code'];

// Canjear code por access_token (short-lived)
$token_url = "https://graph.facebook.com/v22.0/oauth/access_token"
    . "?client_id=" . FB_APP_ID
    . "&redirect_uri=" . urlencode(FB_REDIRECT_URI)
    . "&client_secret=" . FB_APP_SECRET
    . "&code=" . urlencode($code);

$token_response = file_get_contents($token_url);
$token_data = json_decode($token_response, true);

if (empty($token_data['access_token'])) {
    echo "No se pudo obtener el access token inicial.";
    exit;
}

$shortLivedToken = $token_data['access_token'];

// Intercambiar por token de larga duración (long-lived)
$exchange_url = "https://graph.facebook.com/v17.0/oauth/access_token"
    . "?grant_type=fb_exchange_token"
    . "&client_id=" . FB_APP_ID
    . "&client_secret=" . FB_APP_SECRET
    . "&fb_exchange_token=" . urlencode($shortLivedToken);

$exchange_response = file_get_contents($exchange_url);
$exchange_data = json_decode($exchange_response, true);

if (empty($exchange_data['access_token'])) {
    // Si no se devolvió long token, podemos usar el short token (aunque caduca rápido)
    $longLivedToken = $shortLivedToken;
} else {
    $longLivedToken = $exchange_data['access_token'];
}

// Guardamos token en sesión temporal (no en DB todavía) para usar cuando el usuario elija número
$_SESSION['fb_long_token'] = $longLivedToken;

// Obtener la(s) WhatsApp Business Accounts del usuario
$waba_url = "https://graph.facebook.com/v22.0/me/owned_whatsapp_business_accounts?access_token=" . urlencode($longLivedToken);
$waba_resp = file_get_contents($waba_url);
$waba_data = json_decode($waba_resp, true);
if (empty($waba_data['data'][0]['id'])) {
    echo "No se encontró ninguna cuenta de WhatsApp Business asociada a este usuario.";
    exit;
}
$wabaId = $waba_data['data'][0]['id'];

// Guardar WABA en sesión por si se necesita
$_SESSION['fb_waba_id'] = $wabaId;

// Obtener números asociados a la WABA
$phones_url = "https://graph.facebook.com/v22.0/{$wabaId}/phone_numbers?access_token=" . urlencode($longLivedToken);
$phones_resp = file_get_contents($phones_url);
$phones_data = json_decode($phones_resp, true);

if (empty($phones_data['data']) || count($phones_data['data']) === 0) {
    echo "No se encontraron números de teléfono asociados a la cuenta de WhatsApp Business.";
    exit;
}

// Si sólo hay un número: guardamos directamente y cerramos popup
if (count($phones_data['data']) === 1) {
    $phone = $phones_data['data'][0]['display_phone_number'];
    $phone_number_id = $phones_data['data'][0]['id'];

    // Guardar mediante include del script que guarda (evitamos exponer token al cliente)
    $_POST = [
        'phone_number_id' => $phone_number_id,
        'telefono' => $phone,
    ];
    // Llamamos internamente al guardado (require): el archivo save_whatsapp_number.php asumirá sesión y token
    require 'save_whatsapp_number.php';
    // 'save_whatsapp_number.php' imprimirá un HTML/JS para cerrar la ventana y refrescar el opener.
    exit;
}

// Si hay varios números: mostrar selector (HTML simple)
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Seleccionar número WhatsApp</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3">
  <div class="container">
    <h5>Seleccioná el número que querés vincular</h5>
    <p>Empresa: <?php echo htmlspecialchars($_SESSION['fb_link_empresa'] ?? 'N/D'); ?></p>
    <div class="list-group">
      <?php foreach ($phones_data['data'] as $p): 
            $phone = htmlspecialchars($p['display_phone_number']);
            $phone_id = htmlspecialchars($p['id']);
      ?>
        <form method="POST" action="save_whatsapp_number.php" class="mb-2">
            <input type="hidden" name="phone_number_id" value="<?php echo $phone_id; ?>">
            <input type="hidden" name="telefono" value="<?php echo $phone; ?>">
            <button type="submit" class="list-group-item list-group-item-action">
                <?php echo $phone; ?> <small class="text-muted"> (ID: <?php echo $phone_id; ?>)</small>
            </button>
        </form>
      <?php endforeach; ?>
    </div>
    <hr>
    <small>Si cerrás esta ventana sin seleccionar, la vinculación no quedará guardada.</small>
  </div>
</body>
</html>
