<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model{
    protected $table        = 'clientes';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'numero',
        'fecha_creacion',
        'nombre',
        'email',
        'idEmpresa',
        'estado',
    ];
}