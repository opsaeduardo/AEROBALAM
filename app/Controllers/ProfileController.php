<?php
// app/Controllers/ProfileController.php
namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    private UserModel $user;

    public function __construct() { $this->user = new UserModel(); }

    public function info()
    {
        if (!session('login')) return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);

        $row = $this->user
            ->select('Id AS id, Nombre AS nombre, Usuario AS usuario, Correo AS correo, Telefono AS telefono, Edad AS edad, Rol AS rol, Status AS status')
            ->where('Usuario', session('Usuario'))
            ->first();

        return $this->response->setJSON(['status' => 'success', 'data' => $row]);
    }

    public function update()
    {
        if (!session('login')) return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);

        $p = $this->request->getJSON(true);
        if (!$p || empty($p['nombre']) || empty($p['correo'])) return $this->response->setStatusCode(422)->setJSON(['status' => 'error']);

        $this->user->update(session('IdUsuario'), ['Nombre' => $p['nombre'], 'Correo' => $p['correo'], 'Telefono' => $p['telefono'], 'Edad' => $p['edad']]);

        session()->set(['NombreCompleto' => $p['nombre'], 'Correo' => $p['correo'], 'Telefono' => $p['telefono']]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
