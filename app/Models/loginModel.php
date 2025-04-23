<?php

namespace App\Models;

use CodeIgniter\Model;

class loginModel extends Model
{
    protected $tableUsuarios = 'usuario';
    
    // METODO QUE VERIFICA QUE EXISTA EL USUARIO
    public function getusuario($usuario, $contra)
    {
        $consulta = $this->db->table($this->tableUsuarios)
            ->where('usuario', $usuario)
            ->where('Contrasena', $contra)
            ->get();
        if ($consulta->getNumRows() > 0) {
            return $consulta->getResultArray();
        } else {
            return false;
        }
    }

}