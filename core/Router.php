<?php

namespace App\Core;

class Router
{
    /**
     * @var array $routes
     */
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];

    /**
     * Load routes from a file and create a new Router instance.
     *
     * @param string $file The path to the file containing the routes.
     * @return Router The created Router instance.
     */
    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    /**
     * Register a GET route.
     *
     * @param string $uri The URI pattern to match.
     * @param string $controller The controller and action to be called.
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri The URI pattern to match.
     * @param string $controller The controller and action to be called.
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    /**
     * Dispatch the request to the appropriate controller and action.
     *
     * @param string $uri The current URI.
     * @param string $requestType The HTTP request method (GET, POST, etc.).
     * @return mixed The result of the called controller action.
     */
    public function direct($uri, $requestType)
    {
        if (array_key_exists($uri, $this->routes[$requestType])) {

            return $this->callAction(
                ...explode('@', $this->routes[$requestType][$uri])
            );
        }

        http_response_code(404);
        include('error.php');
        die();
    }

    /**
     * Call the specified controller action.
     *
     * @param string $controller The fully qualified controller class name.
     * @param string $action The action method to call.
     * @return mixed The result of the called action.
     */
    protected function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";

        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            http_response_code(404);
            include('error.php');
            die();
        }

        return $controller->$action();

    }
}
