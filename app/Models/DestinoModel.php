<?php

namespace App\Models;

use CodeIgniter\Model;

class DestinoModel extends Model
{
    protected $table            = 'destinos';
    protected $primaryKey       = 'Id';
    protected $returnType       = 'array';

    /**  autoincrement en BD  **/
    protected $useAutoIncrement = true;

    protected $allowedFields    = ['Codigo', 'Nombre', 'Status'];

    /* ─── VALIDACIÓN ─── */
    protected $validationRules = [
        'Id'     => 'permit_empty|is_natural_no_zero',
        'Codigo' => 'required|max_length[10]|is_unique[destinos.Codigo,Id,{Id}]',
        'Nombre' => 'required|max_length[100]',
        'Status' => 'required|in_list[Activo,Inactivo]',
    ];

    protected $validationMessages = [
        'Codigo' => [
            'required'   => 'El código es obligatorio.',
            'max_length' => 'Máximo 10 caracteres.',
            'is_unique'  => 'El código ya existe.',
        ],
        'Nombre' => [
            'required'   => 'El nombre es obligatorio.',
            'max_length' => 'Máximo 100 caracteres.',
        ],
    ];
}
