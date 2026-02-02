<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('orders', 'OrderController::placeOrder');
    $routes->get('orders/summary', 'OrderController::summary');
    $routes->patch('payments/(:num)/status', 'PaymentController::updateStatus/$1');
});

$routes->get('/', 'Home::index');
