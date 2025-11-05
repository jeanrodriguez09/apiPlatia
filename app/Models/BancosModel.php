<?php
namespace App\Models;

use CodeIgniter\Model;

class BancosModel extends Model{
    protected $table        = 'bancos';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [];
}