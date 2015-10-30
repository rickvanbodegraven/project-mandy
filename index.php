<?php
error_reporting(E_ALL);

// register our autoloader for class file resolution
require_once('Core/Autoloader.class.php');
$autoloader = new \Core\Autoloader();

// as early in the chain as possible, activate security functions and headers, before we process any user input
$security = new \Core\Security();

// start up session handling
$session = new \Core\Session();

// register all known routes
$router = new \Core\Routing();

try {
    $output = $router->handleRequest();
} catch (\Exception $ex) {
    die($ex->getMessage());
}

echo $output;
