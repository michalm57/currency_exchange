<?php

/**
 * Application Routes
 *
 * This file defines the routes for the application using the $router instance.
 * Each route maps a URI pattern to a specific controller and action.
 */

//Home
$router->get('', 'CurrencyController@home');

//Currencies
$router->get('currencies', 'CurrencyController@currencies');

//Currrency exchange
$router->get('exchange', 'CurrencyController@exchange');
$router->post('exchange/calculate-exchange', 'CurrencyController@calculateExchange');

//Exchange history
$router->get('history', 'CurrencyController@history');
