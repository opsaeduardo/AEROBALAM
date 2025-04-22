<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/terminos-y-condiciones', 'Home::terms');
$routes->get('/aviso-de-privacidad', 'Home::privacidad');
$routes->get('/registro', 'Home::registro');

$routes->setAutoRoute(true);
