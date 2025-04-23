<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CuponModel;

class CuponesController extends BaseController
{
    protected $cuponModel;

    public function __construct()
    {
        $this->cuponModel = new CuponModel();
        helper(['form']);
    }

    /* GET /cupones */
    public function index()
    {
        return view('dashboard/cupones/index');
    }

    /* GET /cupones/list (AJAX DataTable) */
    public function list()
    {
        return $this->response->setJSON(
            ['data' => $this->cuponModel->findAll()]
        );
    }

    /* POST /cupones/store */
    public function store()
    {
        $data = $this->request->getPost();
        // validar aquí o delegar a un Service
        $this->cuponModel->save($data);
        return $this->response->setJSON(['status' => 'ok']);
    }

    /* PUT /cupones/update/{id} */
    public function update($id = null)
    {
        $this->cuponModel->update($id, $this->request->getRawInput());
        return $this->response->setJSON(['status' => 'ok']);
    }

    /* PATCH /cupones/deactivate/{id} */
    public function deactivate($id = null)
    {
        $this->cuponModel->update($id, ['Status' => 0]);
        return $this->response->setJSON(['status' => 'ok']);
    }

    /* PATCH /cupones/activate/{id} */
    public function activate($id = null)
    {
        $this->cuponModel->update($id, ['Status' => 1]);
        return $this->response->setJSON(['status' => 'ok']);
    }

}
