<?php
declare(strict_types = 1);

// Register auto include function
spl_autoload_register('auto_include');

// Split URL into array
$url = rtrim($_GET['route_url'], '/');
$url_array = explode('/', $url);

// Read mapping
$controller_name = $url_array[0];
$method_name = $url_array[1];
$url_params = array_slice($url_array, 2);

switch($controller_name)
{
    case 'api':
        $controller = new Application\Controllers\APIController;
        $controller->method($method_name, $url_params);
}


function auto_include($classname)
{
    $file = str_replace('\\', '/', $classname) . '.php';

    require_once $file;
}