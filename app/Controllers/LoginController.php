<?php

namespace App\Controllers;

use App\Models\loginModel;
use CodeIgniter\Controller;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login/login');
    }

    // METODO QUE LOGEA AL USUARIO
    public function login()
    {
        $model = new loginModel();

        $usuario = $this->request->getVar('usuario');
        $contra = $this->request->getVar('password');

        $data = $model->getusuario($usuario, $contra);

        if ($data) {

            $session = session();

            $sessionData = [
                'IdUsuario'      => $data[0]['Id'],
                'NombreCompleto' => $data[0]['Nombre'],
                'Rol' => $data[0]['Rol'],
                'Usuario' => $data[0]['Usuario'],
                'Correo' => $data[0]['Correo'],
                'Telefono' => $data[0]['Telefono'],
                'Status' => $data[0]['Status'],
                'login' => true,
            ];

            $session->set($sessionData);

            return $this->response->setJSON([
                'status' => 'success',
                'redirect' => base_url('/dashboard')
            ]);
        } else {

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Usuario o contraseña incorrectos'
            ]);
        }
    }


    // METODO QUE VERIFICA LA SESSION DEL USUARIO
    public function verificarSesion()
    {
        if (session('login')) {
            return json_encode("logeado");
        } else {
            return json_encode("error");
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('/login'));
    }
}
