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

    public function index()
    {
        try {
            return view('dashboard/cupones/index');
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function list()
    {
        try {
            $data = $this->cuponModel->findAll();
            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            if (! $this->validate(['Nombre' => 'required', 'Descuento' => 'required|numeric'])) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['status' => 'fail', 'errors' => $this->validator->getErrors()]);
            }
            if (! $this->cuponModel->save($data)) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['status' => 'fail', 'errors' => $this->cuponModel->errors()]);
            }
            return $this->response->setJSON(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update($id = null)
    {
        try {
            $input = $this->request->getRawInput();
            if (! $this->validate(['Nombre' => 'required', 'Descuento' => 'required|numeric'])) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON(['status' => 'fail', 'errors' => $this->validator->getErrors()]);
            }
            if (! $this->cuponModel->update($id, $input)) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['status' => 'fail', 'errors' => $this->cuponModel->errors()]);
            }
            return $this->response->setJSON(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deactivate($id = null)
    {
        try {
            if (! $this->cuponModel->update($id, ['Status' => 0])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['status' => 'fail', 'errors' => $this->cuponModel->errors()]);
            }
            return $this->response->setJSON(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function activate($id = null)
    {
        try {
            if (! $this->cuponModel->update($id, ['Status' => 1])) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON(['status' => 'fail', 'errors' => $this->cuponModel->errors()]);
            }
            return $this->response->setJSON(['status' => 'ok']);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
