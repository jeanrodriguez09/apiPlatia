<?php

namespace App\Controllers;

class Home extends Auth
{

    public function __construct(){
        $this->authorizedSession(true);
    }

    public function index()
    {
        return redirect()->to('/admin');
    }

    public function login(){
        return redirect()->to('/admin');
    }
    
    public function recuperarContrasena(){
        $data = [
            'scripts' => ['js/pages/login/login.js']
        ];
        
        return view('/pages/login/recuperarContrasena', $data);
    }
    
    public function cliente()
    {
        $data = [
            'scripts' => ['js/pages/login/cliente.js']
        ];
        
        return view('/pages/login/cliente', $data);
    }
    
    public function recuperarContrasenaCliente(){
        $data = [
            'scripts' => ['js/pages/login/cliente.js']
        ];
        
        return view('/pages/login/recuperarContrasenaCliente', $data);
    }
    
    public function autoevaluacion(String $token){
        try {
            $cliente = $this->clientesEvaluacion
                            ->select('clientes_evaluacion.*, CONCAT(v.nombre, " ", v.apellido) as vendedor, hc.id as procesado')
                            ->join('historiales_clientes hc', 'hc.ruc = clientes_evaluacion.ruc AND hc.version = (SELECT VERSION FROM formularios_utoc LIMIT 1)', 'left')
                            ->join('vendedores v', 'v.id = clientes_evaluacion.id_vendedor')
                            ->where('clientes_evaluacion.token', $token)
                            ->first();

            if(empty($cliente)) return view('/errors/html/error_404');

            $encuesta = $this->formulariosUtoc->first();

            $preguntas = $this->formulariosPreguntas
                                ->where('id_formulario', $encuesta['id'])
                                ->findAll();
            
            $respuestas = $this->formulariosRespuestas
                                ->where('id_formulario', $encuesta['id'])
                                ->orderBy('puntuacion', 'desc')
                                ->findAll();

            foreach ($preguntas as $key => $val) {
                if($val['tipo'] != 3) continue;
                $respuestasGeneradas = $this->formulariosRespuestasGeneradas
                                            ->where('id_formulario_pregunta', $val['id'])
                                            ->findAll();

                $preguntas[$key]['respuestas'] = $respuestasGeneradas;
            }

            $data = [
                'title'         => 'Evaluaci&oacute;n de Satisfacci&oacute;n',
                'scripts'       => [
                                    'plugins/parsleyjs/dist/parsley.js',
                                    'js/pages/formularios/satisfaccion.js',
                                ],
                'encuesta'      => $encuesta,
                'preguntas'     => $preguntas,            
                'respuestas'    => $respuestas,            
                'cliente'       => $cliente,            
            ];

            return view('/pages/formularios/satisfaccion', $data);
        } catch (\Throwable $e) {
            log_message('error', 'Error en home/autoevaluacion '.$e);
        }
    }

    public function validarCliente(){
        try{
            $input = (array) $this->request->getPost();
            $ruc = $input['ruc'];

            $cliente = $this->clientesEvaluacion
                            ->select('clientes_evaluacion.*, CONCAT(v.nombre, " ", v.apellido) as vendedor, hc.id as procesado')
                            ->join('historiales_clientes hc', 'hc.ruc = clientes_evaluacion.ruc', 'left')
                            ->join('vendedores v', 'v.id = clientes_evaluacion.id_vendedor')
                            ->where('clientes_evaluacion.ruc', $ruc)
                            ->first();
            
            if(empty($cliente)) return $this->fail(['mensaje' => 'El nro. de RUC dado no pertenece a ninguno de nuestros registros']);
            
            return $this->respond($cliente);
        }catch(\Exception $e){
            log_message('error', 'error en clientes/validarCliente: ' . $e);
            return $this->fail($e);
        }
    }

    public function guardarEvaluacion(){
        if (! $this->validate([
            'id_formulario' => 'required',
            'version'       => 'required',
            'id_cliente'    => 'required',
            'respuestas'    => 'required',
		])) {
			return $this->failValidationErrors($this->validator->getErrors());
		} 

        try{
            $input = $this->request->getPost();
            $id_formulario = $input['id_formulario'];
            $version = $input['version'];
            $id_cliente = $input['id_cliente'];

            log_message('debug', json_encode($input, JSON_PRETTY_PRINT));
            // return $this->respond(['mensaje' => "Preguntas almacenadas con existo!"]);

            $cliente = $this->clientesEvaluacion
                            ->select('
                                id_cliente,
                                id_vendedor,
                                nombre,
                                numero,
                                ruc,
                                correo
                            ')
                            ->find($id_cliente);

            $cliente['id_formulario'] = $id_formulario;
            $cliente['version'] = $version;

            $resultado = $this->historialesClientes->insertarHistoiral($input, $cliente);

            if(empty($resultado)) return $this->fail(['mensaje' => "Ocurrio un error durante el almacenamiento!"]);
            
            return $this->respond(['mensaje' => "Preguntas almacenadas con existo!"]);
        }catch(\Exception $e){
            log_message('error', 'error en formularios/guardarPreguntas: ' . $e);
            return $this->fail($e);
        }
    }
}