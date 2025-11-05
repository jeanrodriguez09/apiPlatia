<?php
namespace App\Models;

use CodeIgniter\Model;

class EmpresasDatosBancariosModel extends Model{
    protected $table        = 'empresas_datos_bancarios';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'idEmpresa',
        'idBanco',
        'numeroCuenta',
        'estado',
        'fechaCreacion',
        'idUsuarioCreador',
    ];
}