<?php
namespace App\Models;

use CodeIgniter\Model;

class ItemsModel extends Model{
    protected $table        = 'items';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'nombre',
        'descripcion',
        'idTipo',
        'idEmpresa',
        'estado',
        'duracion',
        'precio',
        'idUsuarioCreador',
        'fechaCreacion',
        'idUsuarioModificacion',
        'fechaModificacion',
    ];
}