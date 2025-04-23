<?php namespace App\Models;

use CodeIgniter\Model;

class CuponModel extends Model
{

    protected $table      = 'cupones';
    protected $primaryKey = 'Id';

    protected $allowedFields = ['Nombre', 'Descuento', 'Limite', 'Status'];
    protected $useTimestamps = false;
    protected $createdField  = 'FechaCreacion';
}
