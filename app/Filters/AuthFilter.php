<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /** Antes del controlador */
    public function before(RequestInterface $request, $arguments = null)
    {
        // si no hay sesión iniciada…
        if (! session('login')) {
            // Respuesta adecuada para AJAX
            if ($request->isAJAX()) {
                return service('response')
                       ->setJSON(['error' => 'Unauthenticated'])
                       ->setStatusCode(401);
            }
            // …o redirección para peticiones normales
            return redirect()->to(base_url('/login'));
        }
    }

    /** Después del controlador (no lo necesitamos) */
    public function after(RequestInterface $request,
                          ResponseInterface $response,
                          $arguments = null) { }
}
