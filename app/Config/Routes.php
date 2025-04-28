<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/terminos-y-condiciones', 'Home::terms');
$routes->get('/aviso-de-privacidad', 'Home::privacidad');
$routes->get('/registro', 'Home::registro');
$routes->get('/login', 'LoginController::index');



$routes->setAutoRoute(true);





$routes->group('', ['filter' => 'auth'], static function ($routes) {
  /* ───· MAIN VIEW HOME DASHBORAD ·─── */

  $routes->get('/dashboard', 'Home::dashboard');

  /* ───· CUPONES ·─── */

  $routes->get('cupones',          'CuponesController::index');
  $routes->get('cupones/list',     'CuponesController::list');
  $routes->post('cupones/store',   'CuponesController::store');
  $routes->patch('cupones/activate/(:num)', 'CuponesController::activate/$1');
  $routes->put ('cupones/(:num)',   'CuponesController::update/$1');
  $routes->patch('cupones/(:num)',  'CuponesController::deactivate/$1');

  /* ───· PERFIL ·─── */

  $routes->get('perfil/info', 'ProfileController::info');
  $routes->put('perfil',      'ProfileController::update');


  /* ───· DESTINOS ·─── */
  $routes->get   ('destinos',                 'DestinosController::index');
  $routes->get   ('destinos/list',            'DestinosController::list');
  $routes->post  ('destinos/store',           'DestinosController::store');
  $routes->put   ('destinos/(:num)',          'DestinosController::update/$1');
  $routes->patch ('destinos/(:num)',          'DestinosController::deactivate/$1');
  $routes->patch ('destinos/activate/(:num)', 'DestinosController::activate/$1');

  /* ───· USUARIOS ·─── */
  $routes->get('usuarios',          'UsuariosController::index');
  $routes->get('usuarios/list',     'UsuariosController::list');
  $routes->post('usuarios/store',   'UsuariosController::store');
  $routes->put ('usuarios/(:num)',  'UsuariosController::update/$1');
  $routes->patch('usuarios/(:num)', 'UsuariosController::deactivate/$1');
  $routes->patch('usuarios/activate/(:num)', 'UsuariosController::activate/$1');

});
