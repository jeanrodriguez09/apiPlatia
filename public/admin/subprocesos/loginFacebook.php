<?php
session_start();
require_once '../config.php';

// Obtener idEmpresa (por GET o por session si ya la tenías)
$idEmpresa = isset($_GET['idEmpresa']) ? intval($_GET['idEmpresa']) : 0;
if ($idEmpresa <= 0) {
    echo "ID de empresa inválido.";
    exit;
}

// Guardar idEmpresa en sesión para usarlo luego en callback
$_SESSION['fb_link_empresa'] = $idEmpresa;

// Crear state anti-CSRF
$state = bin2hex(random_bytes(16));
$_SESSION['fb_oauth_state'] = $state;

// Permisos requeridos
$scope = 'whatsapp_business_management,whatsapp_business_messaging';

// Construir URL de autorización
$app_id = FB_APP_ID;
$redirect_uri = urlencode(FB_REDIRECT_URI);
$login_url = "https://www.facebook.com/v22.0/dialog/oauth?client_id={$app_id}&redirect_uri={$redirect_uri}&state={$state}&scope={$scope}&response_type=code";

// Redirigir al diálogo de Facebook
header("Location: {$login_url}");
exit;
