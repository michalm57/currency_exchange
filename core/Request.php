<?php

namespace App\Core;

class Request
{
    /**
     * Get the URI (Uniform Resource Identifier) from the request.
     *
     * @return string The trimmed URI path.
     */
    public static function uri()
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    /**
     * Get the HTTP method used in the request.
     *
     * @return string The HTTP method (e.g., GET, POST, PUT, DELETE).
     */
    public static function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
