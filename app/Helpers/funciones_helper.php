<?php
use App\Models\DiasNoHabilesModel;
use App\Models\TiemposEntregaModel;
use Config\Cache;

function verificarRedis(String $alias, Array $filtros = []){
    try {
        $cache = cache();
        $filtros_ = http_build_query($filtros);
        $cacheKey = "{$alias}_{$filtros_}"; // Clave única para cada usuario
        $registros = $cache->get($cacheKey); // 🔍 Verificar si está en caché
        return $registros;
    } catch (\Throwable $e) {
        log_message('error', 'ocurrio un error: '. $e);
        return null;
    }
}

function almacenarRedis(String $alias, Array $filtros = [], $registros, Int $minutos = 5){
    try {
        $cache = cache();
        $filtros_ = http_build_query($filtros);
        $cacheKey = "{$alias}_{$filtros_}"; // Clave única para cada usuario
        $tiempoCache = 60 * $minutos; // Tiempo en segundos (10 minutos)
        $cache->save($cacheKey, $registros, $tiempoCache); // 🔍 Almacena en caché
    } catch (\Throwable $e) {
        log_message('error', 'ocurrio un error: '. $e);
        return null;
    }
}

function is_decimal($n) {return is_numeric($n) && floor($n) != $n;}

function calcularAntiguedad($fechaIngreso){
    $fecha = date('Y-m-d');
    $date1 = new \DateTime($fecha);
    $date2 = new \DateTime(date('Y-m-d', strtotime($fechaIngreso)));
    $diff = $date2->diff($date1);

    $antiguedad = '';
    if($diff->y > 0){
        $text = 'a&ntilde;o';
        if($diff->y > 1) $text = 'a&ntilde;os';
        $antiguedad = "$diff->y $text ";
    }

    if($diff->m > 0){
        $text = 'mes';
        if($diff->m > 1) $text = 'meses';
        if(empty($antiguedad)){
            $antiguedad = "$diff->m $text";
        }else{
            $antiguedad .= "$diff->m $text";
        }
    }

    return $antiguedad;
}

function generarCodigo($total = 8) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $caracteresTotal = strlen($caracteres);
    $codigoRandom = '';

    for ($i = 0; $i < $total; $i++) {
        $codigoRandom .= $caracteres[rand(0, $caracteresTotal - 1)];
    }

    return $codigoRandom;
}

function eliminarLogs(){
    $ruta    = WRITEPATH  . 'logs/';
    $fecha = date('Ymd');
    $filename = 'logsInduventas'.$fecha.'.zip';
    $rutaZip = WRITEPATH  . 'uploads/'.$filename;
    $archivos = opendir($ruta);
    $existeZip = 0;
    while (($archivo = readdir($archivos)) !== false) {
        if(in_array($archivo, array('.log', '..'))) continue;
        if($existeZip == 0){
            $zip = new ZipArchive;
            if ($zip->open($rutaZip, ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$rutaZip>\n"); // puedes lanzar una excepción
            }

            $existeZip++;
        }

        if (filectime($ruta.$archivo) <= time() - 14 * 24 * 60 * 60) {
            log_message('debug', $ruta.$archivo . ' - ' . filectime($ruta.$archivo));
            $zip->addfile($ruta.$archivo); // las demás opciones por defecto
        }
    }

    if($existeZip > 0) $zip->close();

    closedir($archivos); 
}

function calcularDiasNoHabiles($fecha, $tiempo){
    $diasNoHabiles = new DiasNoHabilesModel();

    $fechasNoHabiales1 = $diasNoHabiles->select('CONCAT(fecha,"-",YEAR(NOW()) - 1) AS fecha', false)->findAll();
    $fechasNoHabiales1 = array_column($fechasNoHabiales1, 'fecha');
    $fechasNoHabiales2 = $diasNoHabiles->select('CONCAT(fecha,"-",YEAR(NOW())) AS fecha', false)->findAll();
    $fechasNoHabiales2 = array_column($fechasNoHabiales2, 'fecha');

    $fechasNoHabiales = array_merge($fechasNoHabiales1, $fechasNoHabiales2);
    $tiempoAnalisis = $tiempo;

    // log_message('debug', 'fechasNoHabiales: '.json_encode($fechasNoHabiales));
    // log_message('debug', 'fecha: '.json_encode($fecha));
    $inicio = $tiempoAnalisis == 0 ? 0 : 1;
    for ($i = $inicio; $i <= $tiempoAnalisis; $i++) { 
        $diaEstimado = date('Y-m-d H:i:s', strtotime("+$i day", strtotime($fecha)));
        // log_message('debug', 'diaEstimado al calcular dna: '.($diaEstimado));
        $dia = strftime("%u", strtotime($diaEstimado));

        $diasNoHabiles = array(6,7);
        $fecha_ = date('d-m-Y', strtotime($diaEstimado));
        // log_message('debug', 'fecha_ al calcular dna: '.($fecha_));
        if(in_array($dia, $diasNoHabiles) || in_array($fecha_, $fechasNoHabiales)) $tiempoAnalisis++;
    }

    return $tiempoAnalisis;
}

function calcularDiasHabilesEntrega($fecha, $fechaConfirmacion, Array $datos): string {
    $cumple = 'FALTAN DATOS';
    try {
        if(empty($datos['lista_dias'])) return $cumple;
        
        $diasHabilesEntrega = explode(',', $datos['lista_dias']);
        // log_message('debug', 'diasHabilesEntrega: '.json_encode($diasHabilesEntrega));
        $existeCorteHorario = $datos['existe_corte_horario'] == 1 ? true : false;
        // log_message('debug', 'existeCorteHorario: '.($existeCorteHorario));
        $diaConfirmacion = date('Y-m-d', strtotime($fechaConfirmacion));
        $fechaCreacion = strtotime($fecha);
        $diaCreacionPedido = date('Y-m-d', $fechaCreacion);

        // log_message('debug', 'diaConfirmacion: '.($diaConfirmacion));
        // log_message('debug', 'fechaCreacion: '.($fechaCreacion));
        // log_message('debug', 'diaCreacionPedido: '.($diaCreacionPedido));
        for ($i = 0; $i <= 7; $i++) { 
            $tiempoAnalisis = calcularDiasNoHabiles($diaCreacionPedido, $i);
            // log_message('debug', 'tiempo: '.($tiempoAnalisis));
            $diaEstimado = date('Y-m-d H:i:s', strtotime("+$tiempoAnalisis day", $fechaCreacion));
    
            $dia = strftime("%u", strtotime($diaEstimado));
            // log_message('debug', 'dia: '.($dia));
            if(in_array($dia, $diasHabilesEntrega)){
                $diaEstimado = date('Y-m-d', strtotime($diaEstimado));
                // log_message('debug', 'diaEstimado: '.($diaEstimado));
                if($existeCorteHorario){
                    if($diaEstimado == $diaCreacionPedido){
                        $diaHoraCorte = strtotime("$diaEstimado $datos[corte_horario]");
                        // log_message('debug', 'diaHoraCorte: '.($diaHoraCorte));
                        //SI EL PEDIDO SE GENERA UN DIA HABIL DE ENTREGA Y ANTES DEL CORTE DE HORARIO EL PEDIDO, DEBE DE SER ENTREGADO ESE MISMO DIA
                        if($diaHoraCorte >= $fechaCreacion){
                            $cumple = 'NO';
                            if($diaEstimado == $diaConfirmacion) $cumple = 'SI';
                            break;
                        } 
                        
                        // if ($diaCorte == $diaCreacionPedido && $diaHoraCorte <= $fechaCreacion) continue;
                    }else{
                        //SI NO INGRESA EN LA PRIMERA CONDICION ES PORQUE O NO EXISTE UN HORARIO DE CORTE O EL DIA DE CREACION NO ES IGUAL AL DIA DE ENTREGA, Y TRAS CONTINUAR
                        //EL RECORRIDO LOCALIZO EL PROXIMO DIA HABIL DE ENTREGA Y ES CON ESTE DIA DONDE
                        //DEBO SE DEBE DE COMPARAR CON LA FECHA DE CONFIRMACION
                        // log_message('debug', 'diaConfirmacion: '.($diaConfirmacion));
                        $cumple = 'NO';
                        if($diaConfirmacion <= $diaEstimado) $cumple = 'SI';
                        break;
                    }
                }else{
                    if($diaEstimado == $diaCreacionPedido) continue;
                    
                    //SI NO INGRESA EN LA PRIMERA CONDICION ES PORQUE O NO EXISTE UN HORARIO DE CORTE O EL DIA DE CREACION NO ES IGUAL AL DIA DE ENTREGA, Y TRAS CONTINUAR
                    //EL RECORRIDO LOCALIZO EL PROXIMO DIA HABIL DE ENTREGA Y ES CON ESTE DIA DONDE
                    //DEBO SE DEBE DE COMPARAR CON LA FECHA DE CONFIRMACION
                    // log_message('debug', 'diaConfirmacion: '.($diaConfirmacion));
                    $cumple = 'NO';
                    if($diaConfirmacion <= $diaEstimado) $cumple = 'SI';
                    break;
                }
            }
        }
        // log_message('debug', 'cumple: '.($cumple));
        return $cumple;
    } catch (\Throwable $e) {
        log_message('error', "error al calcular dias de entrega: $e");
        return $cumple;
    }
}

function verificarRangoFechas($desde, $hasta, $hora){
    $horaDesde = Time::createFromFormat('!H:i', $desde);
    $horaHasta = Time::createFromFormat('!H:i', $hasta);
    $horaVerificar = Time::createFromFormat('!H:i', $hora);
    
    if($horaDesde > $horaHasta) $horaHasta->modify('+1 day');

    return ($horaDesde <= $horaVerificar && $horaVerificar <= $horaHasta) || ($horaDesde <= $horaVerificar->modify('+1 day') && $horaVerificar <= $horaHasta);
}

function formatearDecimalExcel($num){
    $separar = explode(',', trim($num));
    // log_message('debug', 'SEPARACION: '.json_encode($separar));
    if(count($separar) > 1){
        $num = str_replace(',', '.', $num);
        if(empty($separar[0])) $num = "0$num";
    }

    return (float) $num;
}

function camposExcel($posicion){
    $abc = array(
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "I",
        "J",
        "K",
        "L",
        "M",
        "N",
        "O",
        "P",
        "Q",
        "R",
        "S",
        "T",
        "U",
        "V",
        "X",
        "Y",
        "Z",
        "AA",
        "AB",
        "AC",
        "AD",
        "AE",
        "AF",
        "AG",
        "AH",
        "AI",
        "AJ",
        "AK",
        "AL",
        "AM",
        "AN",
        "AO",
        "AP",
        "AQ",
        "AR",
        "AS",
        "AT",
        "AU",
        "AV",
    );

    return $abc[$posicion];
}
?>