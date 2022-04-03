<?php
//magic method to call auto-load to use the autoloader in index. php
require_once __DIR__ ."/../vendor/autoload.php";//This makes the class in the app and framework accessible
use Framework\Routing\Router;
$router = new router();
$routes = require_once __DIR__ . "/../app/routes.php";
$routes($router);
print $router->dispatch();//We have not created the dispatch method for our program
//to welcome someone in our website
