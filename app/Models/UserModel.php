<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'Id';
    protected $allowedFields = [
        'Nombre',
        'Correo',
        'Telefono',
        'Status',
        'Usuario',
        'Rol'
    ];
}
