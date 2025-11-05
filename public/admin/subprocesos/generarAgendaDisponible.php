<?php
// subprocesos/generarAgendaDisponible.php
date_default_timezone_set('America/Asuncion');
include '../config.php'; // debe definir $link como mysqli
header('Content-Type: application/json');
ignore_user_abort(true); // Sigue ejecutando aunque el usuario cierre
set_time_limit(0);       // Sin límite de tiempo
fastcgi_finish_request(); // Libera respuesta al navegador y sigue en background

function throwError($msg) {
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

try {
    // Inputs
    if (!isset($_POST['idServicio']) || !isset($_POST['cod_usuario'])) {
        throwError('Faltan parámetros: idServicio y/o cod_usuario');
    }
    $idServicio = intval($_POST['idServicio']);
    $codUsuario = intval($_POST['cod_usuario']);

    // Fecha desde / hasta opcionales
    $hoy = new DateTime('now', new DateTimeZone('America/Asuncion'));
    $fechaHoy = $hoy->format('Y-m-d');

    $fecha_desde = isset($_POST['fecha_desde']) && $_POST['fecha_desde'] ? $_POST['fecha_desde'] : $fechaHoy;
    $fecha_hasta = isset($_POST['fecha_hasta']) && $_POST['fecha_hasta'] ? $_POST['fecha_hasta'] : null;

    // Normalizar fechas
    $dDesde = DateTime::createFromFormat('Y-m-d', $fecha_desde);
    if (!$dDesde) throwError('Formato inválido para fecha_desde (usar YYYY-MM-DD).');
    // forzar desde >= hoy
    if ($dDesde < $hoy) $dDesde = new DateTime($fechaHoy, new DateTimeZone('America/Asuncion'));

    if ($fecha_hasta) {
        $dHasta = DateTime::createFromFormat('Y-m-d', $fecha_hasta);
        if (!$dHasta) throwError('Formato inválido para fecha_hasta (usar YYYY-MM-DD).');
    } else {
        // por defecto 30 días a futuro si no pasa fecha_hasta
        $dHasta = clone $dDesde;
        $dHasta->modify('+30 days');
    }

    if ($dHasta < $dDesde) throwError('fecha_hasta debe ser mayor o igual a fecha_desde.');

    $fecha_desde = $dDesde->format('Y-m-d');
    $fecha_hasta = $dHasta->format('Y-m-d');

    // START TRANSACTION
    if (!$link->begin_transaction()) {
        throwError('No se pudo iniciar transacción: ' . $link->error);
    }

    // 1) Eliminar agenda futura para este servicio (DELETE según tu petición)
    $stmtDel = $link->prepare("DELETE FROM agendaDisponible WHERE idItem = ? AND fecha >= ?");
    if (!$stmtDel) {
        $link->rollback();
        throwError('Error preparando DELETE: ' . $link->error);
    }
    $stmtDel->bind_param('is', $idServicio, $fecha_desde);
    if (!$stmtDel->execute()) {
        $stmtDel->close();
        $link->rollback();
        throwError('Error ejecutando DELETE: ' . $stmtDel->error);
    }
    $stmtDel->close();

    // 2) Obtener duracion del servicio (en minutos)
    $stmtDur = $link->prepare("SELECT duracion FROM items WHERE id = ? LIMIT 1");
    if (!$stmtDur) {
        $link->rollback();
        throwError('Error preparando consulta duracion: ' . $link->error);
    }
    $stmtDur->bind_param('i', $idServicio);
    $stmtDur->execute();
    $resDur = $stmtDur->get_result();
    if (!$resDur) { $stmtDur->close(); $link->rollback(); throwError('Error obteniendo duracion: ' . $stmtDur->error); }
    $rowDur = $resDur->fetch_assoc();
    $stmtDur->close();
    if (!$rowDur || !isset($rowDur['duracion']) || intval($rowDur['duracion']) <= 0) {
        $link->rollback();
        throwError('Duración inválida o no definida para el servicio.');
    }
    $duracionMin = intval($rowDur['duracion']);

    // 3) Cargar excepciones (fechas bloqueadas) en el rango
    $stmtEx = $link->prepare("SELECT fecha FROM itemHorarioExcepciones WHERE idItem = ? AND fecha BETWEEN ? AND ? AND estado = 1");
    if (!$stmtEx) { $link->rollback(); throwError('Error preparando excepciones: ' . $link->error); }
    $stmtEx->bind_param('iss', $idServicio, $fecha_desde, $fecha_hasta);
    $stmtEx->execute();
    $resEx = $stmtEx->get_result();
    $excepcionesSet = [];
    while ($r = $resEx->fetch_assoc()) $excepcionesSet[$r['fecha']] = true;
    $stmtEx->close();

    // 4) Cargar horarios especiales dentro del rango (group by fecha)
    $stmtEsp = $link->prepare("SELECT id, fecha, horaInicio, horaFin, cupos FROM itemsHorariosExcepcionales WHERE idItem = ? AND fecha BETWEEN ? AND ? AND estado = 1 ORDER BY fecha, horaInicio");
    if (!$stmtEsp) { $link->rollback(); throwError('Error preparando especiales: ' . $link->error); }
    $stmtEsp->bind_param('iss', $idServicio, $fecha_desde, $fecha_hasta);
    $stmtEsp->execute();
    $resEsp = $stmtEsp->get_result();
    $especialesByFecha = [];
    while ($r = $resEsp->fetch_assoc()) {
        $especialesByFecha[$r['fecha']][] = $r;
    }
    $stmtEsp->close();

    // 5) Cargar horarios recurrentes (por día 1..7)
    $stmtRec = $link->prepare("SELECT id, dia, horaInicio, horaFin, cupos FROM itemHorarioRecurrente WHERE idItem = ? AND estado = 1 ORDER BY dia, horaInicio");
    if (!$stmtRec) { $link->rollback(); throwError('Error preparando recurrentes: ' . $link->error); }
    $stmtRec->bind_param('i', $idServicio);
    $stmtRec->execute();
    $resRec = $stmtRec->get_result();
    $recurrentesByDia = [];
    while ($r = $resRec->fetch_assoc()) {
        $recurrentesByDia[intval($r['dia'])][] = $r;
    }
    $stmtRec->close();

    // 6) Preparar statement de INSERT en agendaDisponible
    $sqlInsert = "INSERT INTO agendaDisponible 
        (idItem, fecha, horaInicio, horaFin, cuposTotal, cuposDisponibles, origen, idHorario, estado, idUsuarioCreador, fechaCreacion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtIns = $link->prepare($sqlInsert);
    if (!$stmtIns) { $link->rollback(); throwError('Error preparando INSERT agenda: ' . $link->error); }

    // types: i s s s i i s i i i s  -> 'isssiisiiis'
    // bind later inside loop

    $insertsCount = 0;

    // Iterar fechas
    $current = clone $dDesde;
    while ($current <= $dHasta) {
        $fecha = $current->format('Y-m-d');

        // Si la fecha está en excepciones -> omitir
        if (isset($excepcionesSet[$fecha])) {
            $current->modify('+1 day');
            continue;
        }

        // 1) Si hay horarios especiales para la fecha -> usar esos
        if (isset($especialesByFecha[$fecha]) && count($especialesByFecha[$fecha]) > 0) {
            foreach ($especialesByFecha[$fecha] as $esp) {
                $horaInicio = $esp['horaInicio'];
                $horaFin = $esp['horaFin'];
                $cupos = (isset($esp['cupos']) && intval($esp['cupos']) > 0) ? intval($esp['cupos']) : 1;
                // generar slots mediante timestamps
                $startTs = strtotime($fecha . ' ' . $horaInicio);
                $endTs = strtotime($fecha . ' ' . $horaFin);
                if ($startTs === false || $endTs === false || $startTs >= $endTs) continue;
                $slotStart = $startTs;
                while (($slotStart + $duracionMin * 60) <= $endTs) {
                    $slotEnd = $slotStart + $duracionMin * 60;
                    $hIni = date('H:i:s', $slotStart);
                    $hFin = date('H:i:s', $slotEnd);

                    // insertar
                    $origen = 'especial';
                    $idHorario = intval($esp['id']);
                    $estado = 1;
                    $cuposTotal = $cupos;
                    $cuposDisponibles = $cupos;

                    if (!$stmtIns->bind_param('isssiisiiis',
                        $idServicio, $fecha, $hIni, $hFin,
                        $cuposTotal, $cuposDisponibles, $origen, $idHorario,
                        $estado, $codUsuario, $fechaHoy
                    )) {
                        $stmtIns->close();
                        $link->rollback();
                        throwError('Error bind_param: ' . $stmtIns->error);
                    }
                    if (!$stmtIns->execute()) {
                        $stmtIns->close();
                        $link->rollback();
                        throwError('Error insertando agenda: ' . $stmtIns->error);
                    }
                    $insertsCount++;
                    $slotStart += $duracionMin * 60;
                }
            }
            $current->modify('+1 day');
            continue;
        }

        // 2) Si no especiales, usar recurrentes por dia de semana
        $diaSemana = intval($current->format('N')); // 1 (Mon) - 7 (Sun)
        if (!isset($recurrentesByDia[$diaSemana]) || count($recurrentesByDia[$diaSemana]) === 0) {
            $current->modify('+1 day');
            continue;
        }

        foreach ($recurrentesByDia[$diaSemana] as $rec) {
            $horaInicio = $rec['horaInicio'];
            $horaFin = $rec['horaFin'];
            $cupos = (isset($rec['cupos']) && intval($rec['cupos']) > 0) ? intval($rec['cupos']) : 1;

            $startTs = strtotime($fecha . ' ' . $horaInicio);
            $endTs = strtotime($fecha . ' ' . $horaFin);
            if ($startTs === false || $endTs === false || $startTs >= $endTs) continue;

            $slotStart = $startTs;
            while (($slotStart + $duracionMin * 60) <= $endTs) {
                $slotEnd = $slotStart + $duracionMin * 60;
                $hIni = date('H:i:s', $slotStart);
                $hFin = date('H:i:s', $slotEnd);

                // insertar
                $origen = 'recurrente';
                $idHorario = intval($rec['id']);
                $estado = 1;
                $cuposTotal = $cupos;
                $cuposDisponibles = $cupos;

                if (!$stmtIns->bind_param('isssiisiiis',
                    $idServicio, $fecha, $hIni, $hFin,
                    $cuposTotal, $cuposDisponibles, $origen, $idHorario,
                    $estado, $codUsuario, $fechaHoy
                )) {
                    $stmtIns->close();
                    $link->rollback();
                    throwError('Error bind_param: ' . $stmtIns->error);
                }
                if (!$stmtIns->execute()) {
                    $stmtIns->close();
                    $link->rollback();
                    throwError('Error insertando agenda: ' . $stmtIns->error);
                }
                $insertsCount++;
                $slotStart += $duracionMin * 60;
            }
        }

        $current->modify('+1 day');
    }

    // cerrar statement
    $stmtIns->close();

    // COMMIT
    if (!$link->commit()) {
        $link->rollback();
        throwError('Error realizando commit: ' . $link->error);
    }

    echo json_encode(['status' => 'success', 'message' => 'Agenda generada correctamente', 'filas_generadas' => $insertsCount]);

} catch (Exception $e) {
    if (isset($link) && $link->connect_errno === 0) $link->rollback();
    throwError('Excepción: ' . $e->getMessage());
}
