<?php
require_once '../config.php'; // contiene $link = new mysqli(...) o similar
require_once __DIR__ . '/../../core/whatsapp_api.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['numero_destino'], $_POST['mensaje'])) {
        throw new Exception("Faltan datos requeridos.");
    }

    $numero_destino = trim($_POST['numero_destino']);
    $mensaje = trim($_POST['mensaje']);

    if ($numero_destino === '' || $mensaje === '') {
        throw new Exception("Número o mensaje vacío.");
    }

    // Token y número configurado para tu cuenta de WhatsApp Business
    $access_token = 'EAAXgGMeZCX9MBPJqKakKhCRpEugjfOwqcioelaQMhMROSaZB3CuiN88uzbzODPv9ZATN29gZB43zMUo1C1NvZCdL9lTnmFMZA3QCVUpZBfsAjk5mwmJL3sHMJ6fTxZCsPBbvY50qPyfNdTiherSxFJubtaPeno5SgZAWcu3623OquhWVLXO927JUudwXjiL8qAKV15ZAXPNA5xpQd0';
    $phone_number_id = '702345952962357'; // cambia esto por el ID real de tu número
    //Verificamos la existencia del número y obtenemos el ID respectivo.
    $idNumeroWhatsapp=getIdTelefonoWhatsApp($link, $phone_number_id);

    // Enviar mensaje
    $resultado = send_whatsapp_message($access_token, $phone_number_id, $numero_destino, $mensaje, $link, $idNumeroWhatsapp);

    if ($resultado['error']) {
        throw new Exception("Error al enviar: " . $resultado['error']);
    }

    echo json_encode([
        'ok' => true,
        'respuesta_api' => $resultado['result']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage()
    ]);
}
