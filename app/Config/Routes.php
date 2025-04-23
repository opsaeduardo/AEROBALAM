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


$routes->get('/dashboard', 'Home::dashboard');

$routes->setAutoRoute(true);





$routes->group('', ['filter' => 'auth'], static function ($routes) {
  $routes->get('cupones',          'CuponesController::index');
  $routes->get('cupones/list',     'CuponesController::list');
  $routes->post('cupones/store',   'CuponesController::store');
  $routes->put ('cupones/(:num)',   'CuponesController::update/$1');
  $routes->patch('cupones/(:num)',  'CuponesController::deactivate/$1');

});
