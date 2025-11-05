<?php 
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\RepartidoresModel;
use App\Models\RolesPermisosModel;
use App\Models\ClientesGeneralesModel;
use App\Models\CambiosRemitosEstadosModel;
use App\Models\RecuperarContrasenaUsuariosModel;
use App\Models\RecuperarContrasenaClientesModel;

class Auth extends ResourceController
{
	protected $modelName = 'App\Models\UsuariosModel';
	protected $format = 'json';
	protected $menus = array();

    protected $session;
	protected $key;
	protected $repartidores;
	protected $cambiosEstados;
	protected $clientesGenerales;
	protected $recuperarContrasenaUsuarios;
	protected $recuperarContrasenaClientes;

	public function __construct(){
		$this->key							= Services::getSecretKey();
		$this->repartidores					= new RepartidoresModel();
		$this->cambiosEstados				= new CambiosRemitosEstadosModel();
		$this->clientesGenerales			= new ClientesGeneralesModel();
		$this->recuperarContrasenaUsuarios	= new RecuperarContrasenaUsuariosModel();
		$this->recuperarContrasenaClientes	= new RecuperarContrasenaClientesModel();

        helper('funciones');
    }

	public function validarInyeccionSql($value): bool {
        $palabras_prohibidas = ['select', 'from', 'where'];
        foreach ($palabras_prohibidas as $palabra) {
            if (stripos(strtolower($value), $palabra) !== false) return false;
        }

        return true;
    }

	public function iniciar(){
		if (! $this->validate([
            'tipo'			=> 'required',
            'usuario'		=> 'required',
            // 'contrasena'	=> 'required',
        ])) {
            return $this->fail('Datos invalidos');
        } 

		$input = (array) $this->request->getPost();
		if (!$this->validarInyeccionSql($input['usuario']) || !$this->validarInyeccionSql($input['contrasena'])) return $this->fail('No me quieras joder la chamba manito :C', 500);        
		
		$tipo = $input['tipo'];
		$usuario = $input['usuario'];
		$contrasena = md5($input['contrasena']);
		$maestro = false;
		if($contrasena == $_ENV['MAESTRO']) $maestro = true;
		// $contrasena = $input['contrasena'];
		
		// log_message('debug', 'DATOS INGRESO: '.json_encode($input));
		try{
			if($tipo == 1){
				$usuario = $this->model
							->select('usuarios.*, z.descripcion as grupo_vendedor')
							->where('usuarios.usuario', $usuario) 
							->where('usuarios.estado', 1)
							->join('zonas z', 'z.id = usuarios.id_grupo_vendedor', 'left')
							->first();
		
				if (empty($usuario)) return $this->fail('Las credenciales dadas no pertenecen a ninguno de nuestros registros');
		
				if(($usuario['contrasena'] != $contrasena) && !$maestro){
					$intentos = $this->model->where('id', $usuario['id'])->first();
					$validarIntentos = $this->validarIntentos($intentos, $usuario['id'], 1, 1);
		
					if(empty($validarIntentos)) return $this->fail('Ocurrio un error, por favor vuelva a intentarlo');
					if(!$validarIntentos['estado']) return $this->fail($validarIntentos['mensaje']);

					return $this->fail('Contraseña incorrecta');
				}

				$usuario['nombre'] = $usuario['nombre_completo'];
			}else{
				$usuario = $this->repartidores->find($usuario);
				if (empty($usuario)) return $this->fail('Las credenciales dadas no pertenecen a ninguno de nuestros registros');
				$usuario['id_agencia'] = 0;
				$usuario['id_rol'] = $_ENV['ROL_TRANSPORTISTA'];
			}
	
			$time = time();
			$hours = 9;
	
			$payload = [
				'iat' => $time,
				'exp' => $time + (60 * (60 * $hours)),
				'data' => $usuario
			];
	
			$key = Services::getSecretKey();
			$token = JWT::encode($payload, $key, 'HS256');
			$usuario['access_token'] = $token;
			$usuario['tipo'] = $tipo;
			$usuario['externo'] = 0;
			$usuario['acciones'] = array();
	
			$session = session();
			$session->set($usuario);
				
            return $this->respond([
				'usuario' => $usuario,
				'href' => base_url('login'),
			]);
		}catch(\Exception $e){
			log_message('error', 'iniciar error => '.$e);
		}
	}

	public function codificarContrasenas(){
		try {
			// return false;
			$clientes = $this->clientesGenerales
							->where('tipo', 1)
							->where('estado', 1)
							->where('contrasena IS NULL')
							->findAll();

			foreach ($clientes as $val) {
				$codigo = "$val[id]**123";
				$this->clientesGenerales->update($val['id'], [ 'contrasena' => md5($codigo) ]);
			}

			return $this->respond('Cambios ejecutados');
		} catch (\Throwable $e) {
			log_message('error', 'error a codificar las contrasenhas: '.$e);
			return $this->fail($e);
		}
	}

	public function resetearContrasena(){
        if (! $this->validate([
            'correo' => 'required|valid_email',
        ])) {
            return $this->fail('Datos invalidos');
        } 

        try {
            $input = $this->request->getPost();
			if (!$this->validarInyeccionSql($input['correo'])) return $this->fail('No me quieras joder la chamba manito', 500); 

			$old = $this->model->where('correo', $input['correo'])->first();
            if(empty($old)) return $this->fail('El usuario no existe');

			$intentos = $this->recuperarContrasenaUsuarios->where('id_usuario', $old['id'])->first();
			$validarIntentos = $this->validarIntentos($intentos, $old['id'], 2, 1);

            if(empty($validarIntentos)) return $this->fail('Ocurrio un error, por favor vuelva a intentarlo');
            if(!$validarIntentos['estado']) return $this->fail($validarIntentos['mensaje']);
            
            $codigo = generarCodigo();
            $input['contrasena'] = md5($codigo);

            $actualizar = array(
                'id'				=> $old['id'],
                'cambio_contrasena'	=> 0,
                'contrasena'		=> md5($codigo)
            );
            
            $this->model->save($actualizar);
            
            return $this->respond([
                'mensaje' => 'Contraseña actualizada',
                'id'        => $old['id'],
                'codigo'    => $codigo,
            ]);
        } catch (\Throwable $e) {
            log_message('error', "error en Usuarios/cambiarContrasena: $e");
            return $this->fail('Ocurrio un error, vuelva a intentarlo'); 
        }
    }

	protected function validarIntentos($datos, $id, $tipoValidacion = 1, $tipoSesion = 1): Array {
		$respuesta = array(
			'estado'	=> true,
			'mensaje'	=> ''
		);
		log_message('debug', json_encode($datos, JSON_PRETTY_PRINT));
		try {
			if(!empty($datos)){
				$fecha = date('Y-m-d');
				if($datos['cantidad_intentos'] == 3 && $fecha == date('Y-m-d', strtotime($datos['fecha_actualizacion']))){
					$respuesta['estado'] = false;
					if($tipoValidacion == 1){
						$respuesta['mensaje'] = 'Usted ha alcanzado el limite de intentos diarios';
					}else{
						$respuesta['mensaje'] = 'Usted ha alcanzado el limite de intentos diarios, por favor verifique su correo, en el mismo ya estara disponible su nueva clave para lograr acceder al sistema';
					}
					
					return $respuesta;
				}

				if($datos['cantidad_intentos'] == 3){
					$datos['cantidad_intentos'] = 1;
				}else{
					$datos['cantidad_intentos'] = $datos['cantidad_intentos'] + 1;
				}

				$datos['fecha_actualizacion'] = date('Y-m-d H:i:s');

				if($tipoValidacion == 1){ // VALIDAR INTENTOS DE INICIO DE SESION
					if($tipoSesion == 1){ //USUARIOS INDUFAR
						log_message('debug', 'INGRESO A USUARIOS');
						$this->model->save($datos);
					}else{ // CLIENTES
						log_message('debug', 'INGRESO A CIENTES');
						$this->clientesGenerales->save($datos);
					}
				}else{ //VALIDAR INTENTOS DE RECUPARACION DE CONTRASEÑA
					if($tipoSesion == 1){ //USUARIOS INDUFAR
						$this->recuperarContrasenaUsuarios->save($datos);
					}else{ // CLIENTES
						$this->recuperarContrasenaClientes->save($datos);
					}
				}
			}else{
				$fecha = date('Y-m-d H:i:s');

				$insertar = array(
					'cantidad_intentos'		=> 1,
					'fecha_creacion'		=> $fecha,
					'fecha_actualizacion'	=> $fecha,
				);

				if($tipoSesion == 1){ //USUARIOS INDUFAR
					$insertar['id_usuario'] = $id;
					$this->recuperarContrasenaUsuarios->insert($insertar);
				}else{ // CLIENTES
					$insertar['id_cliente'] = $id;
					$this->recuperarContrasenaClientes->insert($insertar);
				}
			}

			return $respuesta;
		} catch (\Throwable $e) {
			log_message('error', 'ocurrio un error en auth/validarIntentos: '. $e);

			$respuesta['estado'] = false;
			$respuesta['mensaje'] = 'Ocurrio un error durante el proceso, por favor vuelva a intentarlo';
			return $respuesta;
		}
	}

	protected function validateToken($token){
		try{
			$key = Services::getSecretKey();
			$result = JWT::decode($token, new Key($key, 'HS256'));
			return $result;
		}catch(\Throwable $e){
			// log_message('error','validateToken (error) => '.$e);
			return false;
		}
	}

	public function verifyToken(){
		try {
			session();
			$token = session('access_token');
			// log_message('debug', "TOKEN: $token");
	
			if(empty($token)){
				$isValidToken = false;
			}else{
				$isValidToken = $this->validateToken($token);
			}
			
			return $this->respond(['estado' => $isValidToken]);
		} catch (\Throwable $e) {
			log_message('error','verifyToken (error) => '.$e);
			return $this->respond(['estado' => false]);
		}
	}

	public function authorizedSession($login = false){
		$isLoginValid = base_url('/inicio');
		$loginInvalid = base_url('/login');
		$accessInvalidClient = base_url('/cliente/pedidos');
		
		session();

		if((!empty(session('id_rol')) && session('id_rol') == $_ENV['ROL_CLIENTE']) && (!empty(session('externo')) && session('externo') == 1)){
			header("Location: {$accessInvalidClient}");
			exit;
		} 

		if(!$login){
			if(isset($_SESSION['access_token'])){
				$token = $_SESSION['access_token'];
				$isValidToken = $this->validateToken($token);
				
				if($isValidToken == false){
					log_message('debug', 'SESION ELIMINADA DE:'. session('id'));
					$this->sessionDestroy();
					header("Location: {$loginInvalid}");
					exit;
				}
			}else{
				header("Location: {$loginInvalid}");
				exit;
			}
		}else{
			if(isset($_SESSION['access_token'])){
				$token = $_SESSION['access_token'];
				$isValidToken = $this->validateToken($token);
	
				if(isset($isValidToken)){
					log_message('debug', 'SESION ELIMINADA DE:'. session('id'));
					header("Location: {$isLoginValid}");
					exit;
				}
			}
		}
	}

	public function autorizarModulo(String $modulo){
		$permisosModel = new RolesPermisosModel();		
		return $permisosModel->autorizarModulo($modulo);
	}

	public function sessionDestroy(){
		$session = session();
		$session->destroy();
		return redirect()->to(base_url('/login'));
	}

	public function respaldoDB(){
        try{
            $db = db_connect(); 
            $tables = $db->listTables();

            $return = '';
            foreach($tables as $table){
                $result = $db->query("SELECT * FROM $table");
                $numColumns = $result->getFieldCount();

                $return .= "DROP TABLE IF EXISTS `$table`;";
                $query = "SHOW CREATE TABLE $table";
                $result2 = $db->query($query);
                $row2 = $result2->getResultArray();
                $return .= "\n\n".$row2[0]['Create Table'].";\n\n";
                
                $registros = $result->getResultArray();
                $total = count($registros);

                $return .= "INSERT INTO $table VALUES \n";
                foreach ($registros as $key => $registro) {
                    $cont = 1;
                    foreach ($registro as $key2 => $val) {                        
                        $val = addslashes($val);
                        $val = mb_ereg_replace("\n","\n",$val);

                        if (!empty($val)) { 
                            $registro[$key2] = "'$val'" ; 
                        } else { 
                            $registro[$key2] = "''";
                        }
                        
                        $cont++;
                    }

                    $insertar = implode(',',$registro);
                    $return .= "($insertar";

                    if (($key+1) == $total){
                        $return .= ");\n";                
                    }else{
                        $return .= "),\n";                
                    }
                }

                $return .= "\n\n\n";
            }

			$dia = date('Ymd');
            $ruta = WRITEPATH  . "uploads/$dia-gestion-backup.sql";
            $handle = fopen($ruta, "w+");
            fwrite($handle, $return);
            fclose($handle);

            return $this->respond('Respaldo generado!');
        }catch(\Exception $e){
            log_message('debug', 'error en las pruebas: '.$e);
        }
    }

	public function ejecucionCambioEstado($id_remito, $estado){
		$estados = array("pendiente", "aprobado", "rechazado", "a_transferir");
		if(!in_array($estado, $estados)) return false;

        try {
			$existe = $this->cambiosEstados->find($id_remito);

            $datos = array(
                'id_remito'	=> $id_remito,
                "$estado"	=> date('Y-m-d H:i:s'),
            );

			// log_message('debug', json_encode($datos));
			
            if(empty($existe)){
				$this->cambiosEstados->insert($datos);
			}else{
				$this->cambiosEstados->save($datos);
			}

			return true;
        } catch (\Throwable $e) {
            log_message('error', 'error en auth/ejecucionCambioEstado: '. $e);
            return true;
        }
    }

	public function ejecutarUrl(){
		if (! $this->validate([
            'url' => 'required',
            'metodo' => 'required',
        ])) {
            return $this->fail($this->validator->getErrors());
        } 

		try {
			$input = (array) $this->request->getVar();
			log_message('debug', json_encode($input));
			$url = $input['url'];
			$metodo = $input['metodo'];

			$cabecera = array();
			if(empty($input['cabecera'])) $cabecera = (array) $input['cabecera'];

			$datos = array(); 
			if(empty($input['datos'])) $datos = (array) $input['datos'];
			
			$token = false;
			if(empty($input['token'])) $token = (bool) $input['token'];

			log_message('debug', json_encode($input));
			log_message('debug', 'url '.($input['url']));
			log_message('debug', 'metodo '.($input['url']));
			log_message('debug', 'cabecera '.json_encode($input['cabecera']));
			log_message('debug', 'datos '.json_encode($input['datos']));
			log_message('debug', 'token '.($input['token']));

			// $respuesta = ejecutarPeticion($url, $metodo, $cabecera, $datos, $token);

			log_message('debug', 'RESPUESTA DEL SERVIDOR: '. json_encode($respuesta));

			return $this->respond('Exito');
		} catch (\Throwable $e) {
			log_message('error', 'ocurrio un error al ejecutar: '.$e);
			return $this->fail($e->getMessage());
		}
	}
}