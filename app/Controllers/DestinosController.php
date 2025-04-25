<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DestinoModel;

class DestinosController extends BaseController
{
    protected $destinoModel;

    public function __construct()
    {
        $this->destinoModel = new DestinoModel();
        helper(['form']);
    }

    /* GET /destinos */
    public function index()
    {
        return view('dashboard/destinos/index');
    }

    /* GET /destinos/list */
    public function list()
    {
        return $this->response->setJSON(['data' => $this->destinoModel->findAll()]);
    }

    /* POST /destinos/store */
    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->destinoModel->insert($data)) {
            return $this->response->setStatusCode(422)
                                   ->setJSON(['errors' => $this->destinoModel->errors()]);
        }

        return $this->response->setStatusCode(201)
                               ->setJSON(['message' => 'Destino creado']);
    }

    /* PUT /destinos/{id} */
    public function update($id)
    {
        $data          = $this->request->getRawInput();
        $data['Id']    = $id;                   // ← necesario para is_unique

        if (!$this->destinoModel->update($id, $data)) {
            return $this->response->setStatusCode(422)
                                   ->setJSON(['errors' => $this->destinoModel->errors()]);
        }

        return $this->response->setJSON(['message' => 'Destino actualizado']);
    }

    /* PATCH /destinos/{id} */
    public function deactivate($id)
    {
        $this->destinoModel->update($id, ['Status' => 'Inactivo']);
        return $this->response->setJSON(['message' => 'Destino desactivado']);
    }

    /* PATCH /destinos/activate/{id} */
    public function activate($id)
    {
        $this->destinoModel->update($id, ['Status' => 'Activo']);
        return $this->response->setJSON(['message' => 'Destino reactivado']);
    }
}
