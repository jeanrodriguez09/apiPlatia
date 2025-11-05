<?php
require_once '../config.php';

try {
    if (!isset($_POST['numero_remitente']) || empty(trim($_POST['numero_remitente']))) {
        throw new Exception('Número remitente no proporcionado.');
    }

    $numeroRemitente = trim($_POST['numero_remitente']);
    $numeroEnvio     = $numeroRemitente;
    $limite = 10;
    $mensajes = [];

    // 1. Mensajes recibidos
    $sqlRecibidos = "
        SELECT mensaje, recibido_en AS fecha 
        FROM mensajes_recibidos 
        WHERE numero_remitente = ? 
        ORDER BY recibido_en DESC 
        LIMIT ?";
    $stmtR = $link->prepare($sqlRecibidos);
    if (!$stmtR) throw new Exception("Error preparando consulta recibidos: " . $link->error);
    $stmtR->bind_param("si", $numeroRemitente, $limite);
    $stmtR->execute();
    $resultR = $stmtR->get_result();

    $countRecibidos = 0;
    while ($row = $resultR->fetch_assoc()) {
        $mensajeLimpio = mb_convert_encoding($row['mensaje'], 'UTF-8', 'UTF-8');
        $mensajes[] = [
            'tipo' => 'recibido',
            'mensaje' => $mensajeLimpio,
            'fecha' => $row['fecha']
        ];
        $countRecibidos++;
    }
    $stmtR->close();

    // 2. Mensajes enviados
    $sqlEnviados = "
        SELECT mensaje, enviado_en AS fecha 
        FROM mensajes_enviados 
        WHERE numero_destino = ? 
        ORDER BY enviado_en DESC 
        LIMIT ?";
    $stmtE = $link->prepare($sqlEnviados);
    if (!$stmtE) throw new Exception("Error preparando consulta enviados: " . $link->error);
    $stmtE->bind_param("si", $numeroEnvio, $limite);
    $stmtE->execute();
    $resultE = $stmtE->get_result();

    $countEnviados = 0;
    while ($row = $resultE->fetch_assoc()) {
        $mensajeLimpio = mb_convert_encoding($row['mensaje'], 'UTF-8', 'UTF-8');
        $mensajes[] = [
            'tipo' => 'enviado',
            'mensaje' => $mensajeLimpio,
            'fecha' => $row['fecha']
        ];
        $countEnviados++;
    }
    $stmtE->close();

    // 3. Log del estado antes de ordenar
    error_log("📥 Recibidos: $countRecibidos | 📤 Enviados: $countEnviados | Total: " . count($mensajes));

    // 4. Ordenar por fecha ascendente
    usort($mensajes, function ($a, $b) {
        return strtotime($a['fecha']) <=> strtotime($b['fecha']);
    });

    // 5. Guardar log en JSON
    $logFile = __DIR__ . '/debug_response.json';
    $jsonString = json_encode($mensajes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if ($jsonString === false) {
        error_log("❌ Error codificando JSON: " . json_last_error_msg());
    } else {
        $resultado = file_put_contents($logFile, $jsonString);
        if ($resultado === false) {
            error_log("❌ No se pudo escribir en el archivo $logFile");
        } else {
            error_log("✅ Log guardado en $logFile con $resultado bytes y " . count($mensajes) . " mensajes.");
        }
    }

    // 6. Respuesta al cliente
    header('Content-Type: application/json');
    echo $jsonString;

} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
