<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Config\Database;

use Firebase\JWT\JWT;
use App\Models\UsuariosModel;
use App\Models\EmpresasModel;
use App\Models\EmpresasDatosBancariosModel;
use App\Models\ItemsModel;
use App\Models\AgendaDisponibleModel;
use App\Models\AgendamientoModel;
use App\Models\ClientesModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class Api extends Auth
{
	protected $modelName = 'App\Models\LlavesModel';
    protected $usuariosModel;
    protected $empresasModel;
    protected $EmpresasDatosBancariosModel;
    protected $itemsModel;
    protected $agendaDisponibleModel;
    protected $agendamientoModel;
    protected $clientesModel;

    protected $db;

    public function __construct(){
        $this->usuariosModel                        = new UsuariosModel();
        $this->empresasModel                        = new EmpresasModel();
        $this->empresasDatosBancariosModel          = new EmpresasDatosBancariosModel();
        $this->itemsModel                           = new ItemsModel();
        $this->agendaDisponibleModel                = new AgendaDisponibleModel();
        $this->agendamientoModel                    = new AgendamientoModel();
        $this->clientesModel                        = new ClientesModel();
        
        $this->db                                   = Database::connect();
        helper('funciones');
    }
    
    public function validarDatos(){
        $auth = $this->autenticarLlave();
        if (!$auth['status']) {
            return $this->fail($auth['message'], $auth['code']);
        }

        try {
            $inputs = (array) $this->request->getVar();
            log_message('debug', 'DATOS PARA SESION APP: '.json_encode($inputs));
    
            $usuario = !empty($inputs['usuario']) ? $inputs['usuario'] : null;
            $pass = !empty($inputs['pass']) ? $inputs['pass'] : null;
    
            if(!$usuario || !$pass){
                return $this->fail('Usuario y contraseña son obligatorios', 400);
            }
    
            // Buscar usuario activo
            $user = $this->usuariosModel
                            ->where('usuarios.usuario', $usuario)
                            ->where('usuarios.estado', 1)
                            ->first();
    
            if(empty($user)){
                return $this->fail('El usuario no está registrado o está inactivo', 400);
            }
    
            // Validar contraseña MD5
            if (!password_verify($pass, $user['contrasena'])) {
                return $this->fail('Contraseña incorrecta', 400);
            }
    
            // Generar token
            $time = time();
            $minutos = 10;
            
            $payload = [
                'iat' => $time,
                'exp' => $time + (60 * $minutos),
                'data' => [
                    'id' => $user['id'],
                    'usuario' => $user['usuario']
                ]
            ];
    
            $key = Services::getSecretKey();
            $token = JWT::encode($payload, $key, 'HS256');
            $data = [
                'token' => $token
            ];
    
            return $this->respond($data, 200);
    
        } catch(\Exception $e) {
            log_message('error', 'Error en validarDatos: '.$e->getMessage());
            return $this->fail('Ocurrió un error interno', 500);
        }
    }

    
    public function usuarios(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();

            // Primero obtenemos los usuarios válidos
            $usuarios = $this->usuariosModel
                              ->findAll();

            log_message('debug', 'TOTAL DE DATOS usuarios: '.count($usuarios));

            return $this->respond($usuarios);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function productos(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();

            $idEmpresa = !empty($inputs['idEmpresa']) ? $inputs['idEmpresa'] : NULL;
            if(empty($idEmpresa)) return $this->fail('Se debe enviar el idEmpresa.');

            // Primero obtenemos los usuarios válidos
            $productos = $this->itemsModel
                             ->select('
                                items.id,
                                items.nombre,
                                items.descripcion,
                                items.duracion,
                                items.precio,
                                items.estado
                             ')
                             ->where('idTipo', 1)
                             ->where('idEmpresa', $idEmpresa)
                             ->findAll();

            log_message('debug', 'TOTAL DE DATOS productos: '.count($productos));
            
            // ✅ Forzar UTF-8 por si hay basura en los datos
            foreach ($productos as &$s) {
                $s['nombre'] = utf8_decode($s['nombre']);
                $s['descripcion'] = utf8_decode($s['descripcion']);
            }

            return $this->response
                        ->setContentType('application/json', 'UTF-8')
                        ->setJSON($productos);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function servicios(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();

            $idEmpresa = !empty($inputs['idEmpresa']) ? $inputs['idEmpresa'] : NULL;
            if(empty($idEmpresa)) return $this->fail('Se debe enviar el idEmpresa.');

            // Primero obtenemos los usuarios válidos
            $servicios = $this->itemsModel
                             ->select('
                                items.id,
                                items.nombre,
                                items.descripcion,
                                items.duracion,
                                items.precio,
                                items.estado
                             ')
                             ->where('idTipo', 2)
                             ->where('idEmpresa', $idEmpresa)
                             ->findAll();
                             
            // ✅ Forzar UTF-8 por si hay basura en los datos
            foreach ($servicios as &$s) {
                $s['nombre'] = utf8_decode($s['nombre']);
                $s['descripcion'] = utf8_decode($s['descripcion']);
            }

            log_message('debug', 'TOTAL DE DATOS servicios: '.count($servicios));

            return $this->response
                        ->setContentType('application/json', 'UTF-8')
                        ->setJSON($servicios);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function empresas(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();

            // Primero obtenemos los usuarios válidos
            $empresas = $this->empresasModel
                             ->select('
                                empresas.id,
                                empresas.nombre,
                                nw.telefono,
                                empresas.activa AS estado
                             ')
                             ->join('numeros_whatsapp nw', 'nw.idEmpresa = empresas.id')
                             ->findAll();
                             
            log_message('debug', 'TOTAL DE DATOS empresas: '.count($empresas));

            // ✅ Forzar UTF-8 por si hay basura en los datos
            foreach ($empresas as &$s) {
                $s['nombre'] = utf8_decode($s['nombre']);
            }
            
            return $this->response
                        ->setContentType('application/json', 'UTF-8')
                        ->setJSON($empresas);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function datosEmpresa(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();
            $telefono = !empty($inputs['telefono']) ? $inputs['telefono'] : NULL;
            
            if(empty($telefono)) return $this->fail('Faltan datos para consultar los datos de la empresa.');

            // Primero obtenemos los usuarios válidos
            $datosEmpresa = $this->empresasModel
                             ->select('
                                empresas.id,
                                empresas.nombre,
                                empresas.email_contacto,
                                empresas.descripcionNegocio,
                                r.descripcion AS rubro,
                                empresas.activa AS estado,
                                empresas.direccion,
                                empresas.latitud,
                                empresas.longitud,
                                empresas.ruc,
                                empresas.reglasBasicas,
                                empresas.reglasRestrictivas
                             ')
                             ->join('numeros_whatsapp nw', 'nw.idEmpresa = empresas.id')
                             ->join('rubros r', 'r.id = empresas.idRubro')
                             ->where('nw.telefono', $telefono)
                             ->first();
            
            if(empty($datosEmpresa)) return $this->fail('No se encontraron datos vinculados al número de teléfono proporcionado.');
            
            $idEmpresa = $datosEmpresa['id'];
            
            $datosBancariosEmpresa = $this->empresasDatosBancariosModel
                                         ->select('
                                           empresas_datos_bancarios.id, 
                                           b.descripcion,
                                           empresas_datos_bancarios.numeroCuenta
                                         ')
                                         ->join('bancos AS b', 'b.id=empresas_datos_bancarios.idBanco')
                                         ->where('idEmpresa', $idEmpresa)
                                         ->where('empresas_datos_bancarios.estado', 1)
                                         ->findAll();

            $datosEmpresa['reglasBasicas'] = html_entity_decode($datosEmpresa['reglasBasicas'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $datosEmpresa['reglasRestrictivas'] = html_entity_decode($datosEmpresa['reglasRestrictivas'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

            function html_to_text($html) {
                $html = str_replace(['<br>', '<br/>', '<br />'], "\n", $html);
                $html = str_replace(['</p>'], "\n", $html);
                $html = strip_tags($html);
                return trim($html);
            }

            $datosEmpresa['reglasBasicas'] = html_to_text($datosEmpresa['reglasBasicas']);
            $datosEmpresa['reglasRestrictivas'] = html_to_text($datosEmpresa['reglasRestrictivas']);
            
            $data = [
                    "datosEmpresa" => $datosEmpresa,
                    "datosBancarios" => $datosBancariosEmpresa
                ];
                             
            log_message('debug', 'TOTAL DE DATOS empresas: '.count($data));

            $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
            $this->response->noCache();

            return $this->respond($data);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function agendaDisponible()
    {
        $validacion = $this->autenticarToken();
        if (empty($validacion)) return $this->fail('Error al validar token de acceso');
    
        try {
            $inputs = (array) $this->request->getVar();
    
            $fecha   = !empty($inputs['fecha']) ? $inputs['fecha'] : null;
            $hora    = !empty($inputs['hora']) ? $inputs['hora'] : null;
            $idItem  = !empty($inputs['idItem']) ? $inputs['idItem'] : null;
            $idEmpresa = !empty($inputs['idEmpresa']) ? $inputs['idEmpresa'] : null;
            
            if(empty($idEmpresa)) return $this->fail('Enviar el identificador de empresa. Falta [idEmpresa].');
    
            // Fecha y hora actual
            $fechaActual = date('Y-m-d');
            $horaActual  = date('H:i:s');
    
            $query = $this->agendaDisponibleModel
                ->select('
                    agendaDisponible.id,
                    agendaDisponible.idItem,
                    i.nombre AS servicio,
                    agendaDisponible.fecha,
                    agendaDisponible.horaInicio,
                    agendaDisponible.horaFin
                ')
                ->join('items i', 'i.id = agendaDisponible.idItem')
                ->where('agendaDisponible.estado', 1)
                ->where('i.idEmpresa', $idEmpresa);
    
            // --- FILTROS DINÁMICOS ---
            if (!empty($fecha)) {
                // Si enviaron fecha
                $query->where('agendaDisponible.fecha', $fecha);
    
                if (!empty($hora)) {
                    // Si también enviaron hora
                    $query->where('agendaDisponible.horaInicio >=', $hora);
                }
            } else {
                // Si NO enviaron fecha
                $query->groupStart()
                        ->where('agendaDisponible.fecha >', $fechaActual)
                        ->orGroupStart()
                            ->where('agendaDisponible.fecha', $fechaActual)
                            ->where('agendaDisponible.horaInicio >=', $horaActual)
                        ->groupEnd()
                      ->groupEnd();
            }
    
            // Filtrar por item si llegó
            if (!empty($idItem)) {
                $query->where('agendaDisponible.idItem', $idItem);
            }
    
            // Ordenar resultados
            $query->orderBy('agendaDisponible.fecha', 'ASC')
                  ->orderBy('agendaDisponible.horaInicio', 'ASC');
    
            $agenda = $query->findAll();
    
            if (empty($agenda)) {
                return $this->failNotFound('No hay agenda disponible.');
            }
    
            foreach ($agenda as &$ag) {
                $ag['servicio'] = utf8_decode($ag['servicio']);
            }
    
            return $this->respond($agenda);
    
        } catch (\Exception $e) {
            log_message('error', 'Error en agendaDisponible: '.$e->getMessage());
            return $this->fail('Ocurrió un error en el servidor.');
        }
    }
    
    public function agendaCliente()
    {
        $validacion = $this->autenticarToken();
        if (empty($validacion)) return $this->fail('Error al validar token de acceso');
    
        $method = $this->request->getMethod(); // post, delete, put, etc.
    
        try {
            switch ($method) {
    
                case 'post':

                    $inputs = (array) $this->request->getVar();
                
                    // Tipo de acción: consultar o insertar
                    $tipoAccion = !empty($inputs['tipoAccion']) ? $inputs['tipoAccion'] : 'consultar';
                
                    if ($tipoAccion === 'insertar') {

                        // Validar entrada
                        $idCliente          = $inputs['idCliente'] ?? null;
                        $idItem             = $inputs['idItem'] ?? null;
                        $idAgendaDisponible = $inputs['idAgendaDisponible'] ?? null;
                        $observacion        = $inputs['observacion'] ?? '';
                        $fechaHoraActual    = date('Y-m-d H:i:s');
                    
                        if (!$idCliente || !$idItem || !$idAgendaDisponible) {
                            return $this->fail("Faltan datos obligatorios para registrar el agendamiento.");
                        }
                    
                        // 1️⃣ Obtener fecha y hora reales
                        $agendaInfo = $this->agendaDisponibleModel
                            ->select('fecha, horaInicio')
                            ->where('id', $idAgendaDisponible)
                            ->where('estado', 1)
                            ->first();
                    
                        if (empty($agendaInfo)) {
                            return $this->fail("El horario seleccionado ya no está disponible.");
                        }
                    
                        $fechaHora = $agendaInfo['fecha'] . ' ' . $agendaInfo['horaInicio'];
                    
                        // 2️⃣ Insertar en agendamiento
                        $nuevoAgendamiento = [
                            'idCliente'          => $idCliente,
                            'idItem'             => $idItem,
                            'idAgendaDisponible' => $idAgendaDisponible,
                            'fechaHora'          => $fechaHora,
                            'observacion'        => $observacion,
                            'estado'             => 1,
                            'fechaCreacion'      => $fechaHoraActual
                        ];
                    
                        $idInsertado = $this->agendamientoModel->insert($nuevoAgendamiento);
                    
                        if (!$idInsertado) {
                            return $this->fail("No se pudo registrar el agendamiento.");
                        }
                            
                        // ✅ Deshabilitar el horario agendado
                        $this->agendaDisponibleModel
                             ->where('id', $idAgendaDisponible)
                             ->set(['estado' => 0])
                             ->update();
                    
                        return $this->respondCreated([
                            'mensaje' => '✅ Agendamiento registrado correctamente',
                            'idAgendamiento' => $idInsertado,
                            'fechaHora' => $fechaHora
                        ]);
                    } else {
                        
                        // === LÓGICA ACTUAL (CONSULTA) ===
                
                        $idCliente = !empty($inputs['idCliente']) ? intval($inputs['idCliente']) : NULL;
                        if (empty($idCliente)) 
                            return $this->fail('Faltan datos para consultar los datos de la agendaCliente.');
                
                        $query = $this->agendamientoModel
                            ->select('
                                agendamiento.id,
                                i.nombre AS servicio,
                                agendamiento.fechaHora,
                                agendamiento.observacion
                            ')
                            ->join('items i', 'i.id = agendamiento.idItem')
                            ->where('agendamiento.idCliente', $idCliente)
                            ->where('agendamiento.estado', 1)
                            ->orderBy('agendamiento.fechaHora', 'ASC');
                
                        $agendamientos = $query->findAll();
                
                        if (empty($agendamientos)) {
                            return $this->failNotFound('El cliente no posee agendamientos.');
                        }
                
                        foreach ($agendamientos as &$ag) {
                            $ag['servicio'] = utf8_decode($ag['servicio']);
                        }
                
                        return $this->respond($agendamientos);
                        
                    }
                    
                case 'delete':
                    // === Nueva lógica para eliminar ===
                    $inputs = (array) $this->request->getVar();
                    $idAgendamiento     = $inputs['idAgendamiento'] ?? null;
                    $fechaHoraActual    = date('Y-m-d H:i:s');
                    $idUsuarioAPI       = 2;
    
                    if (empty($idAgendamiento))
                        return $this->fail('Debe enviar el idAgendamiento para eliminar.');
    
                    $this->agendamientoModel
                        ->where('id', $idAgendamiento)
                        ->set(['estado' => 0, 'fechaModificacion' => $fechaHoraActual, 'idUsuarioModificacion' => $idUsuarioAPI])
                        ->update();
                        
                    // 1️⃣ Obtener fecha y hora reales
                    $agendaInfo = $this->agendamientoModel
                        ->select('idAgendaDisponible')
                        ->where('id', $idAgendamiento)
                        ->first();
                
                    if (empty($agendaInfo)) {
                        log_message('debug', 'No se encontro horario para actualizar estado.');
                    }else{
                        $idAgendaDisponible = $agendaInfo['idAgendaDisponible'];
                        $this->agendaDisponibleModel
                             ->where('id', $idAgendaDisponible)
                             ->set(['estado' => 1])
                             ->update();
                    }
                    
                    return $this->respond(['mensaje' => 'Agendamiento cancelado correctamente.']);
    
                case 'put':
                    $inputs = (array) $this->request->getVar();
                
                    $idAgendamiento     = $inputs['idAgendamiento'] ?? null;
                    $idCliente          = $inputs['idCliente'] ?? null;
                    $idItem             = $inputs['idItem'] ?? null;
                    $idAgendaDisponible = $inputs['idAgendaDisponible'] ?? null;
                    $observacion        = $inputs['observacion'] ?? '';
                    $fechaHoraActual    = date('Y-m-d H:i:s');
                    $idUsuarioAPI       = 2;
                
                    if (empty($idAgendamiento))
                        return $this->fail('El idAgendamiento es obligatorio.');
                
                    // Obtener agendamiento actual
                    $agendaInfoOld = $this->agendamientoModel
                        ->where('id', $idAgendamiento)
                        ->where('estado', 1)
                        ->first();
                
                    if (empty($agendaInfoOld)) {
                        return $this->failNotFound('El agendamiento no existe o ya fue cancelado.');
                    }
                
                    $idAgendaDisponibleOld = $agendaInfoOld['idAgendaDisponible'];
                
                    $this->db->transStart();
                
                    if ($idAgendaDisponible != $idAgendaDisponibleOld) {
                        // ✅ Primero habilitar el viejo turno
                        $this->agendaDisponibleModel
                            ->where('id', $idAgendaDisponibleOld)
                            ->set(['estado' => 1])
                            ->update();
                
                        // ✅ Deshabilitar el nuevo turno para bloquearlo
                        $this->agendaDisponibleModel
                            ->where('id', $idAgendaDisponible)
                            ->where('estado', 1) // Chequeo de disponibilidad
                            ->set(['estado' => 0])
                            ->update();
                
                        // ✅ Actualizar agendamiento
                        $this->agendamientoModel
                            ->where('id', $idAgendamiento)
                            ->set([
                                'idCliente'             => $idCliente,
                                'idItem'                => $idItem,
                                'idAgendaDisponible'    => $idAgendaDisponible,
                                'observacion'           => $observacion,
                                'fechaModificacion'     => $fechaHoraActual,
                                'idUsuarioModificacion' => $idUsuarioAPI
                            ])
                            ->update();
                
                    } else {
                        // ✅ Solo actualizar datos sin cambiar turno
                        $this->agendamientoModel
                            ->where('id', $idAgendamiento)
                            ->set([
                                'idCliente'             => $idCliente,
                                'idItem'                => $idItem,
                                'observacion'           => $observacion,
                                'fechaModificacion'     => $fechaHoraActual,
                                'idUsuarioModificacion' => $idUsuarioAPI
                            ])
                            ->update();
                    }
                
                    // ✅ Finalizar transacción
                    $this->db->transComplete();
                
                    if ($this->db->transStatus() === false) {
                        return $this->fail('No se pudo actualizar el agendamiento. Intente de nuevo.');
                    }
                
                    return $this->respond([
                        'status'   => 'success',
                        'message'  => 'Agendamiento actualizado correctamente',
                        'idAgenda' => $idAgendamiento
                    ], 200);
    
                default:
                    return $this->fail('Método HTTP no permitido.', 405);
            }
    
        } catch (\Exception $e) {
            log_message('error', 'Error en agendaCliente: ' . $e->getMessage());
            return $this->fail('Ocurrió un error en el servidor.');
        }
    }
    
    public function listaClientes(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');
        
        try{
            $inputs = (array) $this->request->getVar();
            $idEmpresa = !empty($inputs['idEmpresa']) ? $inputs['idEmpresa'] : null;

            if(empty($idEmpresa)) return $this->fail('Faltan parametro para ejecutar esta peticion.');
            
            // Primero obtenemos los productos válidos
            $listaClientes = $this->clientesModel
            ->select('id, numero, nombre, email, estado')
            ->where('idEmpresa', $idEmpresa)
            ->findAll();
            
            // ✅ Forzar UTF-8 por si hay basura en los datos
            foreach ($listaClientes as &$lsc) {
                $lsc['nombre'] = utf8_decode($lsc['nombre']);
                
                if($lsc['estado'] != 1){
                    $lsc['estado'] = 'Inactivo';
                }else{
                    $lsc['estado'] = 'Activo';
                }
            }
            
            log_message('debug', 'TOTAL DE DATOS listaClientes: '.count($listaClientes). '. idEmpresa: '. $idEmpresa);

            return $this->respond($listaClientes);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    public function clientes(){
        $validacion = $this->autenticarToken();
        if (empty($validacion)) return $this->fail('Error al validar token de acceso');
    
        $method = $this->request->getMethod(); // post, delete, put, etc.
    
        try {
            switch ($method) {
    
                case 'post':

                    $inputs = (array) $this->request->getVar();
                
                    // Tipo de acción: consultar o insertar
                    $tipoAccion = !empty($inputs['tipoAccion']) ? $inputs['tipoAccion'] : 'consultar';
                
                    if ($tipoAccion === 'insertar') {

                        // Validar entrada
                        $numero             = $inputs['numero'] ?? null;
                        $nombre             = $inputs['nombre'] ?? null;
                        $email              = $inputs['email'] ?? null;
                        $idEmpresa          = $inputs['idEmpresa'] ?? '';
                        $fechaHoraActual    = date('Y-m-d H:i:s');
                    
                        if (!$numero || !$nombre || !$email || !$idEmpresa) {
                            return $this->fail("Faltan datos obligatorios para registrar el cliente.");
                        }
                    
                        // 2️⃣ Insertar en clientes
                        $nuevoCliente = [
                            'numero'             => $numero,
                            'nombre'             => $nombre,
                            'email'              => $email,
                            'idEmpresa'          => $idEmpresa,
                            'estado'             => 1,
                            'fecha_creacion'      => $fechaHoraActual
                        ];
                    
                        $idClienteInsertado = $this->clientesModel->insert($nuevoCliente);
                    
                        if (!$idClienteInsertado) {
                            return $this->fail("No se pudo registrar el cliente.");
                        }
                    
                        return $this->respondCreated([
                            'mensaje' => '✅ cliente registrado correctamente',
                            'idAgendamiento' => $idClienteInsertado
                        ]);
                        
                    } else {
                        
                        // === LÓGICA ACTUAL (CONSULTA) ===
                
                        $telefono = !empty($inputs['numero']) ? intval($inputs['numero']) : NULL;
                        $idEmpresa = !empty($inputs['idEmpresa']) ? intval($inputs['idEmpresa']) : NULL;
                        if (empty($telefono) || empty($idEmpresa)) 
                            return $this->fail('Faltan parametros para consultar los datos del cliente.');
                        
                        $query = $this->clientesModel
                            ->select('
                                clientes.id,
                                clientes.numero,
                                clientes.nombre,
                                clientes.email
                            ')
                            ->where('clientes.numero', $telefono)
                            ->where('clientes.idEmpresa', $idEmpresa)
                            ->where('clientes.estado', 1);
                        
                        $cliente = $query->first();
                        
                        if (empty($cliente)) {
                            return $this->failNotFound('El cliente no existe o esta bloqueado.');
                        }
                        
                        // === SERVICIOS SELECCIONADOS ===
                        $serviciosSeleccionados = $this->agendamientoModel
                            ->select('
                                i.id,
                                i.nombre,
                                agendamiento.fechaHora,
                                agendamiento.observacion
                            ')
                            ->join('items i', 'i.id = agendamiento.idItem')
                            ->where('agendamiento.idCliente', $cliente['id'])
                            ->where('agendamiento.estado', 1)
                            ->findAll();
                        
                        // Normalización texto
                        $cliente['nombre'] = utf8_decode($cliente['nombre']);
                        $cliente['email']  = utf8_decode($cliente['email']);
                        
                        // === ARMAR RESPUESTA ===
                        $respuesta = [
                            'id' => $cliente['id'],
                            'nombre_cliente' => $cliente['nombre'],
                            'telefono_cliente' => $cliente['numero'],
                            'datos_personales' => [
                                'email' => $cliente['email']
                            ],
                            'servicios_seleccionados' => [] // se completa abajo
                        ];
                        
                        // === CARGAR SERVICIOS SELECCIONADOS EN EL JSON FINAL ===
                        foreach ($serviciosSeleccionados as $srv) {
                            $respuesta['servicios_seleccionados'][] = [
                                'id' => $srv['id'],
                                'nombre' => utf8_decode($srv['nombre']),
                                'fecha_hora' => $srv['fechaHora'],
                                'observacion' => utf8_decode($srv['observacion'])
                            ];
                        }
                        
                        return $this->respond($respuesta);
                        
                    }
                    
                case 'delete':
                    // === Nueva lógica para eliminar ===
                    $inputs = (array) $this->request->getVar();
                    $idCliente           = $inputs['idCliente'] ?? null;
                    $fechaHoraActual     = date('Y-m-d H:i:s');
                    $idUsuarioAPI        = 2;
    
                    if (empty($idCliente))
                        return $this->fail('Debe enviar parametros del cliente para bloquear/desactivar.');
    
                    $this->clientesModel
                        ->where('id', $idCliente)
                        ->set(['estado' => 0, 'fechaModificacion' => $fechaHoraActual, 'idUsuarioModificacion' => $idUsuarioAPI])
                        ->update();
                    
                    return $this->respond(['mensaje' => 'Cliente bloqueado correctamente.']);
    
                case 'put':
                    $inputs = (array) $this->request->getVar();
                
                    $idCliente           = $inputs['$idCliente'] ?? null;
                    $numero             = $inputs['numero'] ?? null;
                    $nombre             = $inputs['nombre'] ?? null;
                    $email              = $inputs['email'] ?? null;
                    $idEmpresa          = $inputs['idEmpresa'] ?? '';
                    $fechaHoraActual    = date('Y-m-d H:i:s');
                    $idUsuarioAPI       = 2;
                
                    if (empty($idCliente) || empty($numero) || empty($nombre) || empty($email) || empty($idEmpresa))
                        return $this->fail('Faltan parametros para actualizar los datos del cliente.');
                
                    // Obtener datos del cliente actualidad
                    $clienteInfoOld = $this->clientesModel
                        ->where('id', $idCliente)
                        ->where('estado', 1)
                        ->first();
                
                    if (empty($clienteInfoOld)) {
                        return $this->failNotFound('El cliente no existe o ya fue bloqueado/desactivado.');
                    }
                
                    $this->db->transStart();
                
                        // ✅ Actualizar cliente
                        $this->clientesModel
                            ->where('id', $idCliente)
                            ->set([
                                'numero'             => $numero,
                                'nombre'             => $nombre,
                                'email'              => $email,
                                'idEmpresa'          => $idEmpresa,
                                'fechaModificacion'  => $fechaHoraActual,
                                'idUsuarioModificacion' => $idUsuarioAPI
                            ])
                            ->update();
                
                    // ✅ Finalizar transacción
                    $this->db->transComplete();
                
                    if ($this->db->transStatus() === false) {
                        return $this->fail('No se pudo actualizar el cliente. Intente de nuevo.');
                    }
                
                    return $this->respond([
                        'status'   => 'success',
                        'message'  => 'Cliente actualizado correctamente',
                        'idCliente' => $idCliente
                    ], 200);
    
                default:
                    return $this->fail('Método HTTP no permitido.', 405);
            }
    
        } catch (\Exception $e) {
            log_message('error', 'Error en clientes: ' . $e->getMessage());
            return $this->fail('Ocurrió un error en el servidor.');
        }
    }
    
    public function stockDetalle(){
        $validacion = $this->autenticarToken();

        if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');

        try{
            $inputs = (array) $this->request->getVar();

            // Primero obtenemos los productos válidos
            $productos = $this->productosModel
            ->select('id')
            ->where('estado', 'T')
            ->where('tipo', 1)
            ->findAll();

            // Extraemos solo los IDs
            $idsProductos = array_column($productos, 'id');

            if (empty($idsProductos)) {
                return $this->respond([]); // Retornamos vacío si no hay productos válidos
            }


            $stockDetallado = $this->stockDetalleModel
                               ->select('
                                    stock_detalle.id_producto,
                                    stock_detalle.descripcion_producto,
                                    stock_detalle.lote,
                                    stock_detalle.fecha_vencimiento,
                                    DATEDIFF(stock_detalle.fecha_vencimiento, CURDATE()) AS dias_vence,
                                    stock_detalle.id_almacen,
                                    stock_detalle.cantidad
                               ') // O más campos si querés hacer joins o agregar más info
                               ->whereIn('id_producto', $idsProductos)
                               ->findAll();

            log_message('debug', 'TOTAL DE DATOS stockDetalle: '.count($stockDetallado));

            return $this->respond($stockDetallado);
        }catch(\Exception $e){
            log_message('error', 'error en apiPlatia: '.$e);
            return $this->fail('Ocurrio un error');
        }
    }
    
    // public function crearPedido(){
    //     $validacion = $this->autenticarToken();
    //     if(empty($validacion)) return $this->fail('Ocurrio un error al tratar de validar las credenciales');

    //     if (! $this->validate(
    //         [
    //             'pedidos' => 'required',
    //         ]
    //     )) {
    //         return $this->fail('Datos insuficientes');
    //     }

    //     try{
    //         $inputs = (array) $this->request->getVar();
    //         // log_message('debug', json_encode($inputs, JSON_PRETTY_PRINT));
    //         $pedidos = (array) $inputs['pedidos'];
    //         $ingresados = array();
    //         $eliminar = array();
    //         $rechazados = array();

    //         $productos = $this->productosModel->where('estado', 'T')->where('logica_paquete', 1)->findAll();
    //         $productosIds = array_column($productos, 'id');

    //         $productos2 = $this->productosModel->where('estado', 'T')->findAll();
    //         $productosIds2 = array_column($productos2, 'id');
    //         foreach ($pedidos as $val) {
    //             $val = (array) $val;
    //             if(empty($val['id_agencia']) || empty($val['tipo_documento']) 
    //             || empty($val['id_tipo_venta']) || empty($val['codigo_referencia_app']) 
    //             || empty($val['id_vendedor']) || empty($val['id_cliente']) 
    //             || empty($val['id_condicion_pago']) || empty($val['fecha_creacion']) 
    //             || empty($val['total']) || empty($val['detalles'])) continue;

    //             // OLIVER DOMINSQUI 
    //             // DOMANO@SUDAMERIS.COM.PY
    //             try {
    //                 $id_agencia             = $val['id_agencia'];
    //                 $tipo_documento         = $val['tipo_documento'];
    //                 $id_tipo_venta          = $val['id_tipo_venta'];
    //                 $codigo_referencia_app  = $val['codigo_referencia_app'];
    //                 $id_vendedor            = $val['id_vendedor'];
    //                 $id_cliente             = $val['id_cliente'];
    //                 $id_condicion_pago      = $val['id_condicion_pago'];
    //                 $descuento              = $val['descuento'];
    //                 $total                  = $val['total'];
    //                 $observacion            = $val['observacion'];
    //                 $observacion2           = $val['observacion2'];
    //                 $fecha_creacion         = $val['fecha_creacion'];
    //                 $detalles               = (array) $val['detalles'];
        
    //                 $pedido = array(
    //                     'id_agencia'            => $id_agencia,
    //                     'tipo_documento'        => $tipo_documento,
    //                     'id_tipo_venta'         => $id_tipo_venta,
    //                     'codigo_referencia_app' => $codigo_referencia_app,
    //                     'id_vendedor'           => $id_vendedor,
    //                     'id_cliente'            => $id_cliente,
    //                     'id_condicion_pago'     => $id_condicion_pago,
    //                     'descuento'             => $descuento,
    //                     'total'                 => $total,
    //                     'observacion'           => $observacion,
    //                     'observacion2'          => $observacion2,
    //                     'fecha_creacion'        => $fecha_creacion,
    //                     'fecha_hora_entrega'    => date('Y-m-d H:i:s'),
    //                 );

    //                 if(!empty($val['id_medico']) && $val['id_medico'] != 0) $pedido['id_medico'] = (int) $val['id_medico'];

    //                 $existe = $this->remitosModel
    //                             ->where('codigo_referencia_app', $val['codigo_referencia_app'])
    //                             ->where('tipo_documento', $val['tipo_documento'])
    //                             ->first();
                
    //                 if(!empty($existe)){
    //                     array_push($eliminar, (string) $val['codigo_referencia_app']);
    //                     continue;
    //                 }
    //                 // log_message('debug', json_encode($pedido, JSON_PRETTY_PRINT));
    //                 $id = $this->remitosModel->insert($pedido);

    //                 $aplicarLogicaPaquete = $this->clientesModel
    //                                         ->where('id', $id_cliente)
    //                                         ->where("
    //                                             (EXISTS(
    //                                                 SELECT galp.id_grupo 
    //                                                 FROM grupos_afectados_logica_paquetes galp
    //                                                 WHERE galp.id_grupo = clientes.id_grupo AND galp.tipo = 1
    //                                             )
    //                                             OR
    //                                             EXISTS(
    //                                                 SELECT galp.id_grupo 
    //                                                 FROM grupos_afectados_logica_paquetes galp
    //                                                 WHERE galp.tipo = 2 AND
    //                                                 galp.id_grupo = (SELECT v.id_grupo FROM vendedores v WHERE v.id = clientes.id_vendedor)
    //                                             ))
    //                                         ")
    //                                         ->first();
                    
    //                 foreach ($detalles as $valor) {
    //                     $valor = (array) $valor;
    //                     if(empty($valor['codigo_referencia_app']) || ($valor['codigo_referencia_app'] != $codigo_referencia_app) 
    //                     || empty($valor['id_producto']) || empty($valor['cantidad']) 
    //                     || empty($valor['fecha_creacion']) || empty($valor['id_tipo_precio'])) continue;

    //                     try{
    //                         $id_tipo_precio     = $valor['id_tipo_precio'];
    //                         $id_producto        = $valor['id_producto'];
    //                         $cantidad           = $valor['cantidad'];
    //                         $descuento          = $valor['descuento'];
    //                         $numero_acl         = $valor['numero_acl'];
    //                         $vencimiento_acl    = $valor['vencimiento_acl'];
    //                         $cambiar_almacen    = empty($valor['cambiar_almacen']) ? 0 : 1;
    //                         $fecha_creacion     = $valor['fecha_creacion'];

    //                         $existeProducto = in_array($id_producto, $productosIds);
    //                         $indexProducto = array_search($id_producto, $productosIds2);
    //                         $costo = $productos2[$indexProducto]['costo'] ?? 0;
    //                         /**
    //                          * CONDICIONES A CUMPLIR PARA INGRESAR A LA LOGICA DE PAQUETES
    //                          * 1 - LA VARIABLE "logicaPaquetes" DEBE SER TRUE
    //                          * 2 - LA LOGICA DE PAQUETE DEBE ESTAR ACTIVA PARA EL PRODUCTO
    //                          * 3 - EL CLIENTE DEBE DE PERTENECER AL LISTADO DE GRUPOS AFECTADOS POR LA LOGICA
    //                          */
    //                         if($this->logicaPaquetes && $existeProducto && !empty($aplicarLogicaPaquete)){
    //                             $index = array_search($id_producto, $productosIds);
    //                             $paquete = (int) $productos[$index]['paquete'];
    //                             if($paquete > 0){
    //                                 $calculo = $cantidad / $paquete;
    //                                 $cantidadFinal = ceil($calculo);

    //                                 if(($cantidadFinal * $paquete) > (int) $cantidad){
    //                                     $cantidad = $cantidadFinal * $paquete;
    
    //                                     log_message('debug', "LOGICA DE VENTA POR PAQUETE APLICADO A CLIENTE ($id_cliente), PRODUCTO: $id_producto, CANTIDAD DE $valor[cantidad] A $cantidad");
    //                                 }
    //                             }
    //                         }

    //                         $detalle = array(
    //                             'id_remito'             => $id,
    //                             'id_tipo_venta'         => $id_tipo_venta,
    //                             'id_tipo_precio'        => $id_tipo_precio,
    //                             'id_producto'           => $id_producto,
    //                             'cantidad'              => $cantidad,
    //                             'costo'                 => $costo,
    //                             'descuento'             => $descuento,
    //                             'numero_acl'            => $numero_acl,
    //                             'vencimiento_acl'       => $vencimiento_acl,
    //                             'cambiar_almacen'       => $cambiar_almacen,
    //                             'fecha_creacion'        => $fecha_creacion,
    //                         );

    //                         $this->remitosDetallesModel->insert($detalle);
    //                     }catch (\Exception $errorDetalles) {
    //                         log_message('error', 'error al almacenar un detalle: '.$errorDetalles);
    //                         log_message('error', 'parametros: '.json_encode($valor));
    //                         continue;
    //                     }
    //                 }
                    
    //                 array_push($ingresados, (string) $codigo_referencia_app);

    //                 $datos = array(
    //                     'id_remito'	=> $id,
    //                     'pendiente'	=> date('Y-m-d H:i:s'),
    //                 );
                            
    //                 $this->cambiosEstados->insert($datos);
    //             } catch (\Exception $errorPedidos) {
    //                 log_message('error', 'error al almacenar un pedido: '.$errorPedidos);
    //                 log_message('error', 'parametros: '.json_encode($val));
    //                 array_push($rechazados, (string) $val['codigo_referencia_app']);
    //                 continue;
    //             }
    //         }
            
    //         $respuesta = array(
    //             'ingresados'    => $ingresados,
    //             'rechazados'    => $rechazados,
    //             'eliminar'      => $eliminar,
    //         );
    //         log_message('debug', json_encode($respuesta, JSON_PRETTY_PRINT));
    //         return $this->respond($respuesta);
    //     }catch(\Exception $e){
    //         log_message('error', 'error en apiPlatia: '.$e);
    //         return $this->fail('Ocurrio un error');
    //     }
    // }

    protected function autenticarLlave()
    {
        try {
            $key = trim($this->request->getHeaderLine('X-API-Key'));
            $secret = trim($this->request->getHeaderLine('X-API-Secret'));
            $version = trim($this->request->getHeaderLine('X-API-Version'));
    
            // Validar presencia de headers
            if (!$key || !$secret || !$version) {
                return [
                    'status' => false,
                    'code' => 401,
                    'message' => 'Faltan encabezados de autenticación (X-API-Key, X-API-Secret, X-API-Version)'
                ];
            }
    
            // Buscar en BD
            $existe = $this->model
                ->where('`key`', $key) // Protegido con backticks porque "key" es palabra reservada
                ->where('secret', $secret)
                ->where('version', $version)
                ->first();
    
            if (!$existe) {
                return [
                    'status' => false,
                    'code' => 401,
                    'message' => 'Credenciales inválidas'
                ];
            }
    
            return [
                'status' => true,
                'data' => $existe
            ];
    
        } catch (\Exception $e) {
            log_message('error', 'Error al verificar API Key: '.$e->getMessage());
            return [
                'status' => false,
                'code' => 500,
                'message' => 'Error interno en la autenticación'
            ];
        }
    }



    protected function autenticarToken(){
        try{
            $header = (array) $this->request->getHeaders();
            if(empty($header['Authorization'])) return false;

            $token = explode('Bearer', $header['Authorization']);
            $token = trim($token[1]);

            $esValido = $this->validateToken($token);

            if($esValido == false) return false;

            return true;
        }catch(\Exception $e){
			log_message('error', 'error al verificar: '.$e);
            return false;
        }
    }
}
