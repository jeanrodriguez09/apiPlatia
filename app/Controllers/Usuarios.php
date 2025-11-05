<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RolesModel;
use App\Models\AgenciasModel;
use App\Models\ZonasModel;
use App\Models\VendedoresModel;
use App\Models\VisitadoresModel;

class Usuarios extends Auth
{
	protected $modelName = 'App\Models\UsuariosModel';
    protected $roles;
    protected $agencias;
    protected $vendedores;
    protected $visitadores;
    protected $gruposVendedores;

    public function __construct(){
        $this->authorizedSession();

        $this->roles            = new RolesModel();
        $this->agencias         = new AgenciasModel();
        $this->vendedores       = new VendedoresModel();
        $this->visitadores      = new VisitadoresModel();
        $this->gruposVendedores = new ZonasModel();

        helper('funciones');
    }
    
    public function index(){
        $acciones = $this->autorizarModulo('usuarios');

        $roles = $this->roles->findAll();
        $agencias = $this->agencias->findAll();
        $gruposVendedores = $this->gruposVendedores->findAll();

        $data = [
            'title'             => 'Usuarios',
            'css'               => [
                'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
                'plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'
            ],
            'scripts'           => [
                'js/pages/usuarios/usuarios.js',
                'plugins/datatables.net/js/jquery.dataTables.min.js',
                'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
                'plugins/datatables.net-responsive/js/dataTables.responsive.min.js',
                'plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'
            ],
            'acciones'          => $acciones,
            'roles'             => $roles,
            'agencias'          => $agencias,
            'gruposVendedores'  => $gruposVendedores,
        ];

        return view('/pages/usuarios/index', $data);
    }
    
    public function app(){
        $acciones = $this->autorizarModulo('usuarios/app');

        $data = [
            'title'             => 'Usuarios - APP',
            'css'               => [
                'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
                'plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css'
            ],
            'scripts'           => [
                'js/pages/usuarios/app.js',
                'plugins/datatables.net/js/jquery.dataTables.min.js',
                'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
                'plugins/datatables.net-responsive/js/dataTables.responsive.min.js',
                'plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'
            ],
            'acciones'          => $acciones,
        ];

        return view('/pages/usuarios/app', $data);
    }

    public function listar(){
        try {
            $usuarios = $this->findAll();
    
            if(empty($usuarios)) return $this->respond(['data' => []]);
            $data['data'] = $usuarios;
            return $this->respond($data);
        } catch (\Throwable $e) {
            log_message('error', 'error en usuarios/listar: '. $e);
            return $this->fail($e);
        }
    }
    
    public function listarApp(){
        try {
            $usuarios = $this->model->app();
    
            if(empty($usuarios)) return $this->respond(['data' => []]);
            $data['data'] = $usuarios;
            return $this->respond($data);
        } catch (\Throwable $e) {
            log_message('error', 'error en usuarios: '. $e);
            return $this->fail($e);
        }
    }

    public function selector(){
        try{
            $filtros = (array) $this->request->getPost();

            $resultado = $this->model
                            ->select('id, nombre_completo as text')
                            ->where('id != 1')
                            ->where('estado', 1);

            if(!empty($filtros['id_agencia'])) $resultado->where('id_agencia', $filtros['id_agencia']);
            if(!empty($filtros['buscar'])) $resultado->where("nombre_completo LIKE '%$filtros[buscar]%' OR legajo LIKE '$filtros[buscar]%'");

            $data['results'] = $resultado->findAll();
            
            return $this->respond($data);
        }catch(\Exception $e){
            log_message('error', 'error en documentos: '.$e);
        }
    }
    
    public function crear(){
        if (! $this->validate([
            'legajo'            => "required|is_unique[usuarios.legajo]",
			'id_agencia'        => 'required',
			'id_rol'	        => 'required',
			'nombre_completo'   => 'required',
			'correo'	        => "required|is_unique[usuarios.correo]",
		],
        [   
            'legajo' => [
                'required'  => 'Datos insuficientes',
                'is_unique' => 'Uno de los usuarios ya tiene asignado tal legajo!',
            ],
            'id_agencia' => [
                'required'  => 'Datos insuficientes',
            ],
            'id_rol' => [
                'required'  => 'Datos insuficientes',
            ],
            'nombre_completo' => [
                'required'  => 'Datos insuficientes',
            ],
            'correo' => [
                'required'  => 'El campo de identificacion es requerido',
                'is_unique' => 'Uno de los usuarios ya tiene asignado tal correo!',
            ],
        ])) {
			return $this->failValidationErrors($this->validator->getErrors());
		} 

        try{
            $input = (array) $this->request->getPost();
            $usuario = explode("@", $input['correo']);
            $input['usuario'] = $usuario[0];
            $codigo = generarCodigo();
            $input['contrasena'] = md5($codigo);
            
            $id = $this->model->insert($input);
            
            $idSesion = session('id');
            log_message('debug', "NUEVO USUARIO ($id) CREADO POR USUARIO ($idSesion)");

            return $this->respond([
                'mensaje'   => "Los datos del usuario fueron almacenados exitosamente!",
                'id'        => $id,
                'codigo'    => $codigo,
            ]);
        }catch(\Exception $e){
            log_message('error', 'error en usuarios/crear: ' . $e);
            return $this->fail($e);
        }
    }

    public function actualizar($id){
        if (! $this->validate([
            'legajo'            => "required|is_unique[usuarios.legajo, id, $id]",
			'id_agencia'        => 'required',
			'id_rol'	        => 'required',
			'nombre_completo'   => 'required',
			'correo'	        => "required|is_unique[usuarios.correo, id, $id]",
		],
        [   
            'legajo' => [
                'required'  => 'Datos insuficientes',
                'is_unique' => 'Uno de los usuarios ya tiene asignado tal legajo!',
            ],
            'id_agencia' => [
                'required'  => 'Datos insuficientes',
            ],
            'id_rol' => [
                'required'  => 'Datos insuficientes',
            ],
            'nombre_completo' => [
                'required'  => 'Datos insuficientes',
            ],
            'correo' => [
                'required'  => 'El campo de identificacion es requerido',
                'is_unique' => 'Uno de los usuarios ya tiene asignado tal correo!',
            ],
        ])) {
			return $this->failValidationErrors($this->validator->getErrors());
		} 

        try{
            $input = $this->request->getPost();
            $input['estado'] = empty($input['estado']) ? 0 : 1;
            $usuario = explode("@", $input['correo']);
            $input['usuario'] = $usuario[0];
            
            $oldData = $this->model->find($id);
            if(empty($oldData)) return $this->fail('El usuario no existe');
    
            if(empty($input['id_grupo_vendedor'])) $input['id_grupo_vendedor'] = NULL;
            $this->model->save($input);

            $json_formato = json_encode($input);
            $idSesion = session('id');
            log_message('debug', "USUARIO ($json_formato) ACTUALIZADO POR USUARIO ($idSesion)");
            
            return $this->respond(['mensaje' => "Los datos del usuario $oldData[nombre_completo]($oldData[id]) fueron modificados exitosamente!"]);
        }catch(\Exception $e){
            log_message('error', 'error en usuarios/actualizar: ' . $e);
            return $this->fail($e);
        }
    }

    public function estadoUsuarioApp($id){
        if (! $this->validate([
			'tipo' => 'required',
		])) {
			return $this->failValidationErrors($this->validator->getErrors());
		} 

        try{
            $input = $this->request->getPost();
            $estado = empty($input['estado']) ? 0 : 1;
            $tipo = (int) $input['tipo'];
            
            if($tipo == 1){
                $this->vendedores->update($id, ['estado' => $estado]);
            }else{
                $this->visitadores->update($id, ['estado' => $estado]);
            }

            $json_formato = $estado == 0 ? 'INACTIVADO' : 'REACTIVADO';
            $tipo_texto = $tipo == 1 ? 'VENDEDOR' : 'VISITADOR';
            $idSesion = session('id');
            log_message('debug', "CAMBIO DE ESTADO A $json_formato AL $tipo_texto ($id) ACTUALIZADO POR USUARIO ($idSesion)");
            
            return $this->respond(['mensaje' => "Cambio de estado realizado exitosamente!"]);
        }catch(\Exception $e){
            log_message('error', 'error en usuarios: ' . $e);
            return $this->fail($e);
        }
    }

    public function cambiarContrasena($id = null){
        try {
            if(session('id') == 1) return $this->fail('Accion no permitida');

            if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');
            $oldData = $this->model->find($id);
            if(empty($oldData)) return $this->fail('El usuario no existe');
            
            if (! $this->validate([
                'contrasena'    => 'required|min_length[8]',
                'recontrasena'  => 'required',
            ],
            [  
                'contrasena' => [
                    'min_length' => 'La contraseña debe poseer un minimo de 8 caracteres',
                ],
            ])) {
                return $this->failValidationErrors($this->validator->getErrors());
            } 

            $input = $this->request->getPost();
    
            if ($input['contrasena'] != $input['recontrasena']) return $this->fail('Datos incorrectos');

            $actualizar = array(
                'id' => $id,
                'cambio_contrasena' => 1,
                'contrasena' => md5($input['contrasena'])
            );
            
            $session = session();
			$session->set('cambio_contrasena', 1);

            $this->model->save($actualizar);
            
            $idSesion = session('id');
            log_message('debug', "CAMBIO DE CONTRASEÑA REALIZADO POR USUARIO ($idSesion)");

            return $this->respond(['mensaje' => 'Contraseña actualizada']);
        } catch (\Throwable $e) {
            log_message('error', "error en Usuarios/cambiarContrasena: $e");
            return $this->fail('Ocurrio un error, vuelva a intentarlo'); 
        }
    }

    public function resetearContrasena($id = null){
        try {
            if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');
            $oldData = $this->model->find($id);
            if(empty($oldData)) return $this->fail('El usuario no existe');
            $codigo = generarCodigo();
            $input['contrasena'] = md5($codigo);

            $actualizar = array(
                'id' => $id,
                'cambio_contrasena' => 0,
                'contrasena' => md5($codigo)
            );
            
            $this->model->save($actualizar);
            
            $json_formato = json_encode($actualizar);
            $idSesion = session('id');
            log_message('debug', "RESETEO DE CONTRASEÑA ($json_formato) REALIZADO POR USUARIO ($idSesion)");

            return $this->respond([
                'mensaje' => 'Contraseña actualizada',
                'id'        => $id,
                'codigo'    => $codigo,
            ]);
        } catch (\Throwable $e) {
            log_message('error', "error en Usuarios/cambiarContrasena: $e");
            return $this->fail('Ocurrio un error, vuelva a intentarlo'); 
        }
    }

    public function profile(){
        try{
            $this->authorizedSession();
        
            $menu = $this->getViewModules();
            $where = $this->getViewDataFromRol('users.');
    
            $users      = $this->model->where($where)->first();
            // $roles      = $this->roles->where($where)->findAll();
            // $business   = $this->business->where($where)->findAll();
    
            $token = $_SESSION['access_token'];
            $dataToken = $this->validateToken($token);
            $businessID = $dataToken->data->businessID;
            $rolID = $dataToken->data->rolID;

            $rol      = $this->roles->where('rolID',$rolID )->first();
            $business   = $this->business->where('businessID',$businessID )->first();
    
            $data = [
                'title'     => 'Perfil',
                'css'       => [
                                    
                                ],
                'scripts'   => [
                                    'js/pages/usuarios/profile.js',
                                ],
                'users'     => $users,
                'rol'     => $rol,
                'menu'      => $menu,
                'rolID'     => $rolID,
                'businessID'=> $businessID,
                'business'  => $business
            ];
            // return "ok";
            // var_dump($users);
            return view('/pages/usuarios/profile', $data);
        }catch(\Exception $e){
            return $this->fail($e->getMessage());
        }
    }

    public function profileSave( $id = null ){
        if($id == null) return $this->fail('No se proporciono ninguna idenficacion de usuario');
        $oldData = $this->model->find($id);
        if(empty($oldData)) return $this->fail('El usuario no existe');

        $passOld = $oldData['password']; 
        $input = $this->request->getPost();

        if (!empty($input['password'])) {
            if( $passOld == md5($input['actualPassword']) ){
                $input['password'] = md5($input['password']);
            }else{
                return $this->fail('Contraseña incorrecta.');
            }
        }else{
            unset($input['password']);
        }


        $this->model->update($id, $input);
        
        return $this->respond(['userID' => $id]);
    }

    public function eliminar($id){
        try{
            $oldData = $this->model->find($id);
            if(empty($oldData)) return $this->fail('El registro no existe');

            $this->model->delete($id);
            
            $datosOld = json_encode($oldData);
            $idSesion = session('id');
            log_message('debug', "USUARIO ELIMINADO ($datosOld) POR USUARIO ($idSesion)");
            
            return $this->respond(['mensaje' => "Usuario eliminado con existo!"]);
        }catch(\Exception $e){
            log_message('error', 'error en usuarios/eliminar: ' . $e);
            return $this->fail($e);
        }
    }
}
