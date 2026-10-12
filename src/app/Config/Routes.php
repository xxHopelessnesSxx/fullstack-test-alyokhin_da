<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Comments::index');
$routes->get('comments', 'Comments::items');
$routes->post('comments', 'Comments::create');
$routes->delete('comments/(:num)', 'Comments::delete/$1');
