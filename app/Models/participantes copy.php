<?php

namespace App\Models;

use CodeIgniter\Model;

class Participantes extends Model
{
    protected $tableModulos = 'modulos';
    protected $tablePaseDoble = 'pasedoble';
    protected $tableCupones = 'cupones';
    protected $tableParticipantes = 'participantes';
    protected $tableClientes = 'Clientes';
    protected $campoModulo = 'Modulo';
    protected $campoRegistro = 'Registro';
    protected $dbAmifit;

    public function __construct()
    {
        parent::__construct();
        $this->dbAmifit = \Config\Database::connect('dbAmifit'); // CONEXION CON AMIFIT
    }

    // METODO QUE CONSULTA QUE EL STATUS DEL MODULO
    public function consultarModulos($modulo)
    {
        $consultaStatus = $this->db->table($this->tableModulos)
            ->where($this->campoModulo, $modulo)
            ->select('Status')
            ->get()->getRowArray();

        if ($consultaStatus == null) {
            return json_encode('0');
        } else {
            return $consultaStatus;
        }
    }

    // METODO QUE VALIDA QUE LA KEYFOB SEA VALIDA
    public function validarKeyfob($keyfob)
    {
        $validacionKeyfob = $this->dbAmifit->table($this->tableClientes)
            ->where('KeyFob', $keyfob)
            ->get()->getRowArray();

        if ($validacionKeyfob == null) {
            return 0;
        } else {
            return $validacionKeyfob;
        }
    }

    // METODO QUE VERIFICA EL LIMITE DE REGISTROS 
    public function verificarLimiteRegistros()
    {
        $validacionLimite = $this->db->table($this->tableModulos)
            ->where($this->campoModulo, $this->campoRegistro)
            ->select('Limite')
            ->get()->getRow();

        if ($validacionLimite != null) {

            // SE OBTIENE EL LIMITE
            $limite = $validacionLimite->Limite;

            // CONTAR LOS PARTICIPANTES CON STATUS DIFERENTE DE CANCELADO
            $validacionParticipantes = $this->db->table($this->tableParticipantes)
                ->where('StatusPago !=', 'Cancelado')
                ->where('StatusPago !=', '')
                ->countAllResults();

            // VALIDAR SI LOS PARTICIPANTES NO EXCEDEN EL LIMITE PERMITIDO
            if ($validacionParticipantes >= $limite) {
                return 'LimiteExcedido';
            } else {
                return json_encode($validacionParticipantes);
            }
        }
    }


    // METODO QUE VALIDA QUE SOLO EXISTA UN PASE DOBLE
    public function verificarPaseDoble($keyfob)
    {
        $validacionPaseDoble = $this->db->table($this->tablePaseDoble)
            ->where('KeyFob', $keyfob)
            ->countAllResults();

        // SI ES 1, SIGNIFICA QUE YA HAY OFERTA 2X1 APLICADA
        if ($validacionPaseDoble == 0) {
            return 'PagoCompleto';
        }
        if ($validacionPaseDoble == 1) {
            return 'RegistroGratis';
        }
        
        if ($validacionPaseDoble == 2) {
            return 'OfertaAplicada';
        }
        
    }

    // METODO QUE CANCELA EL PASE DOBLE
    public function cancelarPaseDoble($id)
    {
        $this->db->table($this->tablePaseDoble)
            ->where('IdParticipante', $id)
            ->delete();
    }

    // METODO QUE VALIDA QUE EL CUPON ESTE ACTIVO Y HAYA DISPONIBLES
    public function validarCupones($cupon)
    {
        $validacionCupones = $this->db->table($this->tableCupones)
            ->where('Cupon', $cupon)
            // ->where('Status', 'Activo')
            ->get()->getRowArray();

        if ($validacionCupones == null) {
            return 'NoExisteCupon';
        } else {
            ($validacionCupones['Status'] == 'Activo') ? $cupon = 'CuponActivo' : $cupon = 'CuponInactivo';
            return $cupon;
        }
    }

    // METODO QUE VALIDA QUE EL CUPON ACTIVO NO HAYA PASADO EL LIMITE DISPONIBLE
    public function validarLimiteCupones($cupon)
    {
        $validacionLimiteCupones = $this->db->table($this->tableCupones)
            ->where('Cupon', $cupon)
            ->get()->getRowArray();
        $respuesta = $validacionLimiteCupones;
        $limite = $validacionLimiteCupones["Limite"];

        // SE CONSULTA LA TABLA DE PARTICIPANTES Y SE HACE CONTEOS DE CUANTOS OCUPARON EL CUPON
        $participantesCupones = $this->db->table($this->tableParticipantes)
            ->where('Cupon', $cupon)
            ->countAllResults();

        if ($participantesCupones >= $limite) {
            return 'CuponLleno';
        } else {
            return $respuesta;
        }
    }

    // METODO QUE REGISTRA UN PARTICIPANTE
    public function agregarParticipante($data)
    {
        $agregar = $this->db->table($this->tableParticipantes)
            ->insert($data);

        return $this->db->insertID();
    }

    // METODO QUE INSERTA EL REGISTRO DE PASEDOBLE
    public function agregarPaseDoble($data)
    {
        $agregar = $this->db->table($this->tablePaseDoble)
            ->insert($data);

        return $this->db->insertID();
    }

    // METODO QUE PONE EL PAGO COMO CANCELADO EL REGISTRO
    public function cancelarRegistro($id, $data)
    {
        $cancelarRegistro = $this->db->table($this->tableParticipantes)
            ->where('Id', $id);
        return $cancelarRegistro->update($data);


        // $query = $this->db->table('pasarelapagos');
        // $query->where("IdPago", $idPago);
        // return $query->update($data);
    }

    // METODO QUE PONE EL PAGO COMO CANCELADO EL REGISTRO
    public function actualizarURL($id, $data)
    {
        $cancelarRegistro = $this->db->table($this->tableParticipantes)
            ->where('Id', $id);
        return $cancelarRegistro->update($data);
    }

    // ACTUALIZAR REGISTRO
    public function actualizarRegistro($id, $data)
    {
        $cancelarRegistro = $this->db->table($this->tableParticipantes)
            ->where('Id', $id);
        return $cancelarRegistro->update($data);
    }

    // METODO QUE TRAE EL ULTIMO NUMERO DE PARTICIPANTE
    public function ultimoNumeroParticipante()
    {
        $ultimoParticipante = $this->db->table($this->tableParticipantes)
            ->select('NumeroParticipante')
            ->orderBy('NumeroParticipante', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($ultimoParticipante["NumeroParticipante"] == null || $ultimoParticipante["NumeroParticipante"] == 0) {
            return 'SinNumeroParticipante';
        } else {
            return $ultimoParticipante['NumeroParticipante'];
        }
    }

    // METODO QUE VERIFICA QUE YA TENGA ASIGNADO UN NUMERO DE PARTICIPANTE
    public function verificarNumeroParticipante($id)
    {
        $validacionNumeroParticipante = $this->db->table($this->tableParticipantes)
            ->where('Id', $id)
            ->select('NumeroParticipante')
            ->get()->getRowArray();
    
        
        if ($validacionNumeroParticipante["NumeroParticipante"] == '0' || $validacionNumeroParticipante["NumeroParticipante"] == null) {
            return 'NoExisteNumeroParticipante';
        } else {
            return 'ExisteNumeroParticipante';
        }
    }

    // METODO QUE TRAE LA INFORMACION DEL PARTICIPANTE
    public function informacionRegistro($id)
    {
        $infoRegistro = $this->db->table($this->tableParticipantes)
        ->where('Id', $id)
        ->get()->getRowArray();

        return $infoRegistro;
    }


    public function getClientes()
    {
        $clientes = $this->dbAmifit->table('Clientes')
            ->get()
            ->getResult();

        return $clientes;
    }

}
