<?php
namespace App\Models;

use CodeIgniter\Model;

class EmpresasModel extends Model{
    protected $table        = 'empresas';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nombre',
        'email_contacto',
        'descripcionNegocio',
        'idRubro',
        'activa',
        'creada_en',
        'idUsuarioResponsable',
        'direccion',
        'latitud',
        'longitud',
        'ruc',
    ];
}