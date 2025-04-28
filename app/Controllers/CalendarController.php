<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VueloModel;

class CalendarController extends BaseController
{
    private VueloModel $vueloModel;

    public function __construct() { $this->vueloModel = new VueloModel(); }

    /** GET /calendar */
    public function index() { return view('dashboard/calendar/index'); }

    /** GET /calendar/events  →  JSON para FullCalendar */
    public function events()
    {
        $raw = $this->vueloModel->getCalendarEvents();

        $events = array_map(function($r){
            // Colores según estado
            $colors = [
                'Activo'   => '#38b000',   // verde
                'Cancelado'=> '#e63946',   // rojo
                'Completo' => '#6c757d'    // gris
            ];
            return [
                'id'    => $r['Id'],
                'title' => "{$r['Origen']} → {$r['Destino']}",
                'start' => $r['start'],
                'color' => $colors[$r['Estado']] ?? '#0d6efd'
            ];
        }, $raw);

        return $this->response->setJSON($events);
    }

    /** GET /calendar/detail/{id}  →  info vuelo + pasajeros */
    public function detail($id)
    {
        return $this->response->setJSON(
            $this->vueloModel->getVueloDetalle((int)$id)
        );
    }
}
