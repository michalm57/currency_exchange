<?php

/**
 * Application Entry Point - Index File
 *
 * This file serves as the entry point for the application.
 * It includes the necessary autoload.php and bootstrap.php files.
 * It also utilizes the Request and Router classes to handle incoming requests and direct them to the appropriate routes.
 */

require 'vendor/autoload.php';
require 'core/bootstrap.php';

use App\Core\Request;
use App\Core\Router;

Router::load('app/routes.php')
    ->direct(Request::uri(), Request::method());
