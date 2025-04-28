<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UsuariosController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form']);
    }

    public function index()
    {
        return view('dashboard/usuarios/index');
    }

    public function list()
    {
        return $this->response->setJSON(['data' => $this->userModel->findAll()]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        if (!$this->userModel->insert($data)) {
            return $this->response->setStatusCode(422)
                                   ->setJSON(['errors' => $this->userModel->errors()]);
        }

        return $this->response->setStatusCode(201)
                               ->setJSON(['message' => 'Usuario creado exitosamente']);
    }

    public function update($id)
    {
        $data = $this->request->getRawInput();
        $data['Id'] = $id;

        if (!$this->userModel->update($id, $data)) {
            return $this->response->setStatusCode(422)
                                   ->setJSON(['errors' => $this->userModel->errors()]);
        }

        return $this->response->setJSON(['message' => 'Usuario actualizado']);
    }

    public function deactivate($id)
    {
        $this->userModel->update($id, ['Status' => 'Inactivo']);
        return $this->response->setJSON(['message' => 'Usuario desactivado']);
    }

    public function activate($id)
    {
        $this->userModel->update($id, ['Status' => 'Activo']);
        return $this->response->setJSON(['message' => 'Usuario activado']);
    }
}
