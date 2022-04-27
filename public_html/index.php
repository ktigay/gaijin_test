<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

spl_autoload_register(function($class) {
    require_once (__DIR__ .'/../src/'. str_replace('\\', '/', $class) . '.php');
});

use App\App;

$uri = $_SERVER['REQUEST_URI'];
App::getInstance()->run($uri);