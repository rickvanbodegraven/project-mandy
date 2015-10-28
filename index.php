<?php
error_reporting(E_ALL);

// because we use classes that are named 'Classname.class.php'
spl_autoload_extensions('.class.php');
spl_autoload_register();

// register all of our routes
$route = new \Config\Routing();

// instantiate a front controller
$controller = new \Core\Controller();



