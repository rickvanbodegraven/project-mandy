<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'Core/Autoloader.class.php');

// register said autoloader
$autoloader = new \Core\Autoloader();

// instantiate the application handler now that we know where to find the class
$application = new \Core\Application();

// as early in the chain as possible, activate security functions and headers, before we process any user input
$application
    ->register(new \Core\Security())
    ->register(new \Core\Session())  // start up session handling
    ->register(new \Core\Routing()); // register router

return $application;
