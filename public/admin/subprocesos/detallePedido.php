<?php
include '../config.php';

$response = [
    'status' => 'error',
    'message' => '',
    'data' => []
];

if ($link->connect_error) {
    $response['message'] = "Error de conexión: " . $link->connect_error;
    echo json_encode($response);
    exit;
}

if (!empty($_POST['idPedido'])) {
    $idPedido = intval($_POST['idPedido']);

    $sql = "SELECT 
                p.id AS codigo,
                c.nombre AS nombreCliente,
                c.numero,
                p.total,
                CASE
                    WHEN p.direccion IS NOT NULL AND TRIM(p.direccion) != '' THEN 
                        p.direccion
                    ELSE
                        CASE 
                            WHEN p.pickUp = 1 THEN 'Retira del local'
                            ELSE ''
                        END
                END AS direccionFinal,
                dp.idItem,
                i.nombre AS denominacionItem,
                dp.cantidad,
                dp.precioUnitario,
                dp.subTotal
            FROM pedidos p
            INNER JOIN detalle_pedidos dp ON p.id = dp.idPedido
            INNER JOIN items i ON i.id = dp.idItem
            INNER JOIN clientes c ON c.id = p.idCliente
            WHERE p.id = $idPedido";

    $resultado = $link->query($sql);

    if ($resultado) {
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $response['data'][] = [
                    'codigo' => $fila['codigo'],
                    'nombreCliente' => $fila['nombreCliente'],
                    'numero' => $fila['numero'],
                    'total' => intval($fila['total']),
                    'direccion' => $fila['direccionFinal'],
                    'idItem' => $fila['idItem'],
                    'denominacionItem' => $fila['denominacionItem'],
                    'cantidad' => intval($fila['cantidad']),
                    'precioUnitario' => intval($fila['precioUnitario']),
                    'subTotal' => intval($fila['subTotal'])
                ];
            }
            $response['status'] = 'success';
        } else {
            $response['message'] = 'No se encontraron registros.';
        }
    } else {
        $response['message'] = "Error en consulta SQL: " . $link->error;
    }
} else {
    $response['message'] = "Parámetro 'idPedido' no recibido.";
}

echo json_encode($response);
$link->close();
?>
