<?php

namespace App\Controllers;

class Inicio extends Auth
{
    public function index(){ 
        $this->authorizedSession();
        
        $data = [
            'title'     => 'Bienvenido/a',
            'css'       => [
            ],
            'scripts'   => [
                'js/pages/inicio/inicio.js'
            ],
            'acciones' => []
        ];

        return view('/pages/inicio/index', $data);
    }
}
