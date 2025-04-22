<?php

namespace App\Models;

use CodeIgniter\Model;

class Participantes extends Model
{
    protected $tableDestinos = 'Destinos';
    protected $tableVuelos = 'Vuelos';
    protected $tableReservas = 'Reservas';
    protected $tablePasajeros = 'Pasajeros';
    protected $tableReservaPagos = 'ReservaPago';
    protected $tablePagos = 'Pagos';

    /* METODO QUE CONSULTA LOS DESTINOS DISPONIBLES */
    public function consultarDestinos()
    {
        $consulta = $this->db->table($this->tableDestinos)
            ->where('Status', 'Activo')
            ->get()->getResultArray();

        if ($consulta == null) {
            return json_encode('0');
        } else {
            return $consulta;
        }
    }

    /* METODO QUE CONSULTA LAS FECHAS DISPONIBLES */
    public function consultarFecha($Origen_Id)
    {
        $this->db->query("SET lc_time_names = 'es_ES'");

        $builder = $this->db->table('Vuelos v');
        $builder->select('v.Id, v.Precio, DATE_FORMAT(v.Fecha, "%W %d %M") AS Fecha, DATE_FORMAT(v.Fecha, "%Y-%m-%d") AS FechaISO, v.Hora, (v.Asientos_Disponibles - COUNT(r.Id)) AS Asientos_Libres', false);
        // $builder->select('v.Id, v.Precio, DATE_FORMAT(v.Fecha, "%W %d %M") AS Fecha, v.Hora, (v.Asientos_Disponibles - COUNT(r.Id)) AS Asientos_Libres', false);
        $builder->join('Reservas r', 'v.Id = r.Vuelo_Id AND r.Estado = "Reservado"', 'left');
        $builder->where('v.Origen_Id', $Origen_Id);
        $builder->where('v.Estado', 'Activo');
        $builder->where('v.Fecha >= CURDATE()');
        $builder->groupBy('v.Id');
        $builder->orderBy('v.Fecha', 'ASC');
        $builder->orderBy('v.Hora', 'ASC');


        $consulta = $builder->get()->getResultArray();

        if (empty($consulta)) {
            return 0;
        } else {
            return $consulta;
        }
    }

    /* REGISTRAR PASAJEROS */
    public function registrarPasajeros($nombre, $correo, $telefono, $edad, $genero)
    {
        $data = [
            'Nombre' => $nombre,
            'Correo' => $correo,
            'Telefono' => $telefono,
            'Edad' => $edad,
            'Genero' => $genero,
            'Fecha_Registro' => date('Y-m-d H:i:s'),
        ];

        $this->db->table($this->tablePasajeros)->insert($data);
        return $this->db->insertID();
    }

    /* METODO QUE REGISTRA EN RESERVAS */
    public function registrarReserva($reserva = [])
    {
        if (empty($reserva)) {
            return false;
        }

        $this->db->table($this->tableReservas)->insert($reserva);
        return $this->db->insertID();
    }


    /* METODO QUE REGISTRA EN RESERVAPAGO */
    public function agregarReservaPago($idReservas = [], $pagoData = [])
    {
        if (empty($idReservas) || empty($pagoData)) {
            return false;
        }

        // Insertar en tabla Pagos
        $this->db->table($this->tablePagos)->insert($pagoData);
        $idPago = $this->db->insertID();

        // Insertar relación en ReservaPago
        foreach ($idReservas as $reservaId) {
            $this->db->table($this->tableReservaPagos)->insert([
                'IdPago'    => $idPago,
                'IdReserva' => $reservaId
            ]);
        }

        return $idPago;
    }

    /* METODO QUE CONSULTA LOS PAGOS */
    public function revisarPago($idPago)
    {
        $consulta = $this->db->table($this->tablePagos)
            ->where('Id', $idPago)
            ->get()->getResultArray();

        return $consulta;
    }

    /* METODO QUE CANCELA LA RESERVA */
    public function cancelarReserva($idReserva, $data = [])
    {
        return $this->db->table($this->tableReservas)
            ->where('Id', $idReserva)
            ->update($data);
    }

    /* METODO QUE CANCELA EL PAGO */
    public function cancelarPago($idPago, $data = [])
    {
        return $this->db->table($this->tablePagos)
            ->where('Id', $idPago)
            ->update($data);
    }

    /* ACTUALIZA Y TRAE INFOMRAICON DE LOS PASAJEROS */
    public function actualizarURL($idPago)
    {
        $resultado = $this->db->table('pagos p')
            ->select(
                'rp.Id AS ReservaPagoId, p.TipoPago, p.Total, p.ComisionPorcentaje, p.ComisionFija, p.IVA, p.MontoTotal, p.StatusPago,
                pas.Nombre, pas.Telefono, pas.Correo, pas.Edad, pas.Genero,
                ori.Codigo AS Origen_Codigo, ori.Nombre AS Origen_Nombre,
                des.Codigo AS Destino_Codigo, des.Nombre AS Destino_Nombre,
                v.Fecha AS Vuelo_Fecha, v.Hora AS Vuelo_Hora'
            )
            ->join('reservaPago rp', 'rp.IdPago = p.Id')
            ->join('reservas r', 'r.Id = rp.IdReserva')
            ->join('vuelos v', 'v.Id = r.Vuelo_Id')
            ->join('destinos ori', 'ori.Id = v.Origen_Id')   // origen
            ->join('destinos des', 'des.Id = v.Destino_Id')  // destino
            ->join('pasajeros pas', 'pas.Id = r.Pasajero_Id')
            ->where('p.Id', $idPago)
            ->get()
            ->getResultArray();

        $dataPago = [
            'StatusPago' => 'Pagado',
        ];

        $this->db->table($this->tablePagos)
            ->where('Id', $idPago)
            ->update($dataPago);

        if (!$resultado) {
            return ['error' => 'Pago no encontrado'];
        }

        return $resultado;
    }

    /* SE CONSULTA LA INFORMACION ATRAVES DE ID RESERVA PAGO */
    public function informacionRegistro($id)
    {
        $resultado = $this->db->table('pagos p')
            ->select(
                'rp.Id AS ReservaPagoId, p.TipoPago, p.Total, p.ComisionPorcentaje, p.ComisionFija, p.IVA, p.MontoTotal, p.StatusPago,
         pas.Nombre, pas.Telefono, pas.Correo, pas.Edad, pas.Genero,
         ori.Codigo AS Origen_Codigo, ori.Nombre AS Origen_Nombre,
         des.Codigo AS Destino_Codigo, des.Nombre AS Destino_Nombre,
         v.Fecha AS Vuelo_Fecha, v.Hora AS Vuelo_Hora'
            )
            ->join('reservaPago rp', 'rp.IdPago = p.Id')
            ->join('reservas r', 'r.Id = rp.IdReserva')
            ->join('vuelos v', 'v.Id = r.Vuelo_Id')
            ->join('destinos ori', 'ori.Id = v.Origen_Id')
            ->join('destinos des', 'des.Id = v.Destino_Id')
            ->join('pasajeros pas', 'pas.Id = r.Pasajero_Id')
            ->where('rp.Id', $id)
            ->get()
            ->getRowArray();

        if (!$resultado) {
            return ['error' => 'ReservaPago no encontrada'];
        }

        return $resultado;
    }

    /* INFORMACION CONSTANTE DEL PAGO, SI ESTA PAGADO O NO */
    public function informacionPago($id)
    {
        $resultado = $this->db->table($this->tablePagos)
            ->where('Id', $id)
            ->get()
            ->getRowArray();

        return $resultado;
    }
}
