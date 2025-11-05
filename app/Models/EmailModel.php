<?php
namespace App\Models;

use CodeIgniter\Model;

class EmailModel extends Model{
    protected $table        = 'email';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'host',
        'usuario',
        'contrasena',
        'estado',
        'principal',
    ];
}