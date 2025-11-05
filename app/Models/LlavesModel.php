<?php
namespace App\Models;

use CodeIgniter\Model;

class LlavesModel extends Model{
    protected $table        = 'llaves';
    protected $id           = 'id';
    protected $primaryKey   = 'id';
    // protected $returnType     = 'array';
    // protected $useSoftDeletes = true;

    protected $allowedFields = [];
}