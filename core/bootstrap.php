<?php

use App\Core\App;

App::bind('config', require 'config.php');

App::bind('database', new QueryBuilder(
    Connection::make(App::get('config')['database'])
));

/**
 * Render a view by including the corresponding view file.
 *
 * @param string $name The name or path of the view file to render.
 * @param array $data An optional associative array of data to pass to the view.
 * @return mixed The result of including the view file.
 */
function view($name, $data = [])
{
    extract($data);
    return require "app/views/{$name}.view.php";
}

/**
 * Redirect the user to the specified path.
 *
 * @param string $path The path or URL to redirect to.
 * @return void
 */
function redirect($path)
{
    header("Location: /{$path}");
}
