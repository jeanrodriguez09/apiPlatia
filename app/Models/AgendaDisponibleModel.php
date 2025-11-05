<?php
namespace App\Models;

use CodeIgniter\Model;

class AgendaDisponibleModel extends Model{
    protected $table        = 'agendaDisponible';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'idItem',
        'fecha',
        'horaInicio',
        'horaFin',
        'cuposTotal',
        'cuposDisponibles',
        'origen',
        'estado',
        'idHorario',
        'idUsuarioCreador',
        'fechaCreacion',
        'idUsuarioModificacion',
        'fechaModificacion',
    ];
}