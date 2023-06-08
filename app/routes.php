<?php

/**
 * Application Routes
 *
 * This file defines the routes for the application using the $router instance.
 * Each route maps a URI pattern to a specific controller and action.
 */

$router->get('', 'PagesController@home');
$router->get('currencies', 'PagesController@currencies');
