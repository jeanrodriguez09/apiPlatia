<?php
namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model{
    protected $table        = 'usuarios';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [
        'legajo',
        'id_rol',
        'id_agencia',
        'id_grupo_vendedor',
        'usuario',
        'nombre_completo',
        'correo',
        'contrasena',
        'estado',
        'cambio_contrasena',
        'cantidad_intentos',
        'fecha_actualizacion',
    ];

    public function app($filtros = []){
        try {
            $where = false;
            $sql = "SELECT 
                    v.id
                    , CONCAT(v.nombre, ' ', IFNULL(v.apellido, '')) as nombre_completo
                    , v.estado
                    , v.id_agencia
                    , a.descripcion as agencia
                    , 1 as tipo
                    FROM vendedores v
                    JOIN agencias a ON a.id = v.id_agencia
                    GROUP BY v.id
                    UNION
                    SELECT 
                    v.id
                    , CONCAT(v.nombre, ' ', IFNULL(v.apellido, '')) as nombre_completo
                    , v.estado
                    , v.id_agencia
                    , a.descripcion as agencia
                    , 2 as tipo
                    FROM visitadores v
                    JOIN agencias a ON a.id = v.id_agencia
                    WHERE v.tipo = 'A'
                    GROUP BY v.id";

            $usuarios = $this->db->query($sql)->getResultArray();

            return $usuarios;
        } catch (\Throwable $e) {
            log_message('error', "ocurrio un error: $e");
            return [];
        }
    }
}