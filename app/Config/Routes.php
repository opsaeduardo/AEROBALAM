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
  $routes->get('/dashboard', 'Home::dashboard');
  
  $routes->get('cupones',          'CuponesController::index');
  $routes->get('cupones/list',     'CuponesController::list');
  $routes->post('cupones/store',   'CuponesController::store');
  $routes->patch('cupones/activate/(:num)', 'CuponesController::activate/$1');
  $routes->put ('cupones/(:num)',   'CuponesController::update/$1');
  $routes->patch('cupones/(:num)',  'CuponesController::deactivate/$1');

  $routes->get('perfil/info', 'ProfileController::info');
  $routes->put('perfil',      'ProfileController::update');

});
