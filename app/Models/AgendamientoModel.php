<?php
namespace App\Models;

use CodeIgniter\Model;

class AgendamientoModel extends Model{
    protected $table        = 'agendamiento';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'idItem',
        'idAgendaDisponible',
        'idCliente',
        'fechaHora',
        'estado',
        'fechaCreacion',
        'fechaModificacion',
        'idUsuarioModificacion',
        'observacion',
    ];
}