<?php

/**
 * Application Routes
 *
 * This file defines the routes for the application using the $router instance.
 * Each route maps a URI pattern to a specific controller and action.
 */

//Home
$router->get('', 'PagesController@home');

//Currencies
$router->get('currencies', 'PagesController@currencies');

//Currrency exchange
$router->get('exchange', 'PagesController@exchange');
$router->post('exchange/calculate-exchange', 'PagesController@calculateExchange');

//Exchange history
$router->get('history', 'PagesController@history');
