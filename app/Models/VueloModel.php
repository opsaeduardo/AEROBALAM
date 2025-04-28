<?php
namespace App\Models;

use CodeIgniter\Model;

class VueloModel extends Model
{
    protected $table         = 'vuelos';
    protected $primaryKey    = 'Id';
    protected $allowedFields = [
        'Origen_Id','Destino_Id','Fecha','Hora',
        'Asientos_Disponibles','Precio','Estado'
    ];

    /**  ▼  Eventos para FullCalendar  */
    public function getCalendarEvents(): array
    {
        return $this->select("vuelos.Id,
                              d1.Nombre AS Origen,
                              d2.Nombre AS Destino,
                              CONCAT(vuelos.Fecha,' ',vuelos.Hora)   AS start,
                              vuelos.Estado")
                    ->join('destinos d1', 'd1.Id = vuelos.Origen_Id')
                    ->join('destinos d2', 'd2.Id = vuelos.Destino_Id')
                    ->findAll();
    }

    /**  ▼  Detalle de vuelo + pasajeros  */
    public function getVueloDetalle(int $vueloId): array
    {
        $vuelo = $this->select("vuelos.*,
                                d1.Nombre AS Origen,
                                d2.Nombre AS Destino")
                      ->join('destinos d1','d1.Id = vuelos.Origen_Id')
                      ->join('destinos d2','d2.Id = vuelos.Destino_Id')
                      ->find($vueloId);

        $pasajeros = $this->db->table('reservas')
                              ->select("reservas.Id,
                                        pasajeros.Nombre,
                                        pasajeros.Telefono,
                                        pasajeros.Correo,
                                        reservas.Asiento,
                                        reservas.Estado   AS EstadoReserva,
                                        pagos.StatusPago  AS EstadoPago")
                              ->join('pasajeros','pasajeros.Id = reservas.Pasajero_Id')
                              ->join('reservapago','reservapago.IdReserva = reservas.Id','left')
                              ->join('pagos','pagos.Id = reservapago.IdPago','left')
                              ->where('reservas.Vuelo_Id',$vueloId)
                              ->get()->getResultArray();

        return ['vuelo'=>$vuelo,'pasajeros'=>$pasajeros];
    }
}
