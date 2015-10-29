<?php
error_reporting(E_ALL);

// register our autoloader for class file resolution
require_once('Core/Autoloader.class.php');
$autoloader = new \Core\Autoloader();

// as early in the chain as possible, activate security functions and headers, before we process any user input
$security = new \Core\Security();

// register all of our routes
try {
    $router = new \Config\Routing();
} catch (\Exception $ex) {
    // if an exception occurs, show the message and kill the request
    die($ex->getMessage());
}
