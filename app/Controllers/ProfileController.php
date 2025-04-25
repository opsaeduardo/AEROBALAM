<?php
// app/Controllers/ProfileController.php
namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    private UserModel $user;

    public function __construct()
    {
        $this->user = new UserModel();
    }

    public function info()
    {
        if (! session('login')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $row = $this->user
            ->select('Nombre AS nombre, Usuario AS usuario, Correo AS correo, Telefono AS telefono, Rol AS rol')
            ->where('Usuario', session('Usuario'))
            ->first();

        return $this->response->setJSON(['status' => 'success', 'data' => $row]);
    }

    public function update()
    {
        if (! session('login')) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error']);
        }

        $payload = $this->request->getJSON(true);

        if (!$payload || empty($payload['nombre']) || empty($payload['correo'])) {
            return $this->response->setStatusCode(422)->setJSON(['status' => 'error']);
        }

        $this->user->update(
            session('IdUsuario'),
            [
                'Nombre'   => $payload['nombre'],
                'Correo'   => $payload['correo'],
                'Telefono' => $payload['telefono']
            ]
        );

        session()->set([
            'NombreCompleto' => $payload['nombre'],
            'Correo'         => $payload['correo'],
            'Telefono'       => $payload['telefono']
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
